<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Diskon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PenggunaanDiskon;
use App\Models\Pelanggan;



class DiskonController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Pencarian diskon
        $diskon = Diskon::when($search, function ($query, $search) {
            return $query->where('nama_diskon', 'like', "%{$search}%")
                ->orWhere('id_diskon', 'like', "%{$search}%");
        })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('diskon.index', [
            'diskon' => $diskon,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('diskon.create');
    }
    private function sendWhatsappNotification($target, $message)
{
    $token = env('FONNTE_TOKEN');

    $response = Http::withHeaders([
        'Authorization' => $token
    ])->post('https://api.fonnte.com/send', [
        'target' => $target,
        'message' => $message,
    ]);

    logger()->info('Fonnte Response:', $response->json());

    return $response->json();
}

    public function sendWA($target, $message)
    {
        $token = env('FONNTE_TOKEN');
    
        $response = Http::withHeaders([
            'Authorization' => $token
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
    
        Log::info('FONNTE_RESPONSE', [
            'target' => $target,
            'response' => $response->json()
        ]);
        
        return $response->json();
    }
    

public function store(Request $request)
{
    // Convert minimal pembayaran
    $request->merge([
        'minimal_pembayaran' => str_replace('.', '', $request->minimal_pembayaran),
    ]);

    $validated = $request->validate([
        'nama_diskon' => 'required|string|max:255',
        'jumlah_potongan' => 'required|numeric|min:0',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'minimal_pembayaran' => 'nullable|numeric|min:0',
        'minimal_transaksi' => 'nullable|integer|min:0',
    ]);

    // Generate ID
    $lastDiskon = Diskon::orderBy('id_diskon', 'desc')->first();
    if ($lastDiskon) {
        $lastIdNumber = (int) substr($lastDiskon->id_diskon, 3);
        $nextIdNumber = $lastIdNumber + 1;
    } else {
        $nextIdNumber = 1;
    }

    $nextId = 'DSK' . str_pad($nextIdNumber, 6, '0', STR_PAD_LEFT);

    // Simpan diskon
    $diskon = Diskon::create([
        'id_diskon' => $nextId,
        'nama_diskon' => $validated['nama_diskon'],
        'jumlah_potongan' => $validated['jumlah_potongan'],
        'minimal_pembayaran' => $validated['minimal_pembayaran'] ?? 0,
        'tanggal_mulai' => $validated['tanggal_mulai'],
        'tanggal_selesai' => $validated['tanggal_selesai'],
        'minimal_transaksi' => $validated['minimal_transaksi'] ?? 0,
    ]);

    // Ambil semua pelanggan
    $pelanggan = Pelanggan::all();

    // Kirim WA
    foreach ($pelanggan as $p) {
        $nomor = $p->NoTelp_Pelanggan;

        // Format 08 -> 62
        if (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        $message =
            "*Diskon Baru!* \n" .
            "Nama: {$diskon->nama_diskon}\n" .
            "Potongan: {$diskon->jumlah_potongan}\n" .
            "Berlaku: {$diskon->tanggal_mulai} s/d {$diskon->tanggal_selesai}\n\n" .
            "Jangan sampai ketinggalan!";

        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN')
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $message,
        ]);

        // Logging response
        Log::info('FONNTE_RESPONSE', [
            'target' => $nomor,
            'response' => $response->json()
        ]);
    }

    return redirect()
        ->route('diskon.index')
        ->with('success', 'Diskon berhasil dibuat dan notifikasi WhatsApp telah dikirim.');
}

    public function edit($diskon)
    {
        $diskon = Diskon::findOrFail($diskon);
        return view('diskon.edit', compact('diskon'));
    }

    public function update(Request $request, $diskon)
    {
        $request->validate([
            'nama_diskon' => 'required|string|max:255',
            'jumlah_potongan' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'minimal_pembayaran' => 'required|numeric|min:0',
            'minimal_transaksi' => 'required|numeric|min:0',
        ]);

        $diskon = Diskon::findOrFail($diskon);

        $diskon->update([
            'nama_diskon' => $request->nama_diskon,
            'jumlah_potongan' => $request->jumlah_potongan,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'minimal_pembayaran' => $request->minimal_pembayaran,
            'minimal_transaksi' => $request->minimal_transaksi,
        ]);
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil diperbarui.');
    }


    public function destroy($id)
    {
        Diskon::findOrFail($id)->delete();
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dihapus.');
    }

    public function gunakan(Request $request, $id)
    {
        $request->validate([
            'diskon_id' => 'required',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        // CEK jika pelanggan sudah pernah pakai diskon ini
        $sudah = PenggunaanDiskon::where('id_loyalitas', $pelanggan->id_loyalitas)
            ->where('id_diskon', $request->diskon_id)
            ->first();

        if ($sudah) {
            return back()->with('error', 'Diskon ini sudah pernah digunakan.');
        }

        // SIMPAN
        PenggunaanDiskon::create([
            'id_diskon'     => $request->diskon_id,
            'ID_Loyalitas'  => $pelanggan->id_loyalitas,
            'tanggal_pakai' => now(),
        ]);

        return back()->with('success', 'Diskon berhasil digunakan.');
    }
}
