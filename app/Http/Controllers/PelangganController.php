<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Loyalitas;
use App\Models\Diskon;
use App\Models\Transaction;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PelangganImport;
use Illuminate\Support\Facades\DB;
use App\Models\PenggunaanDiskon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PelangganController extends Controller
{
    // Tampilkan semua pelanggan
    public function index()
    {

        $loyalitas = Loyalitas::with('pelanggan')->get();
        return view('loyalitas.index', compact('loyalitas'));
    }

    // Form tambah pelanggan
    public function create()
    {
        return view('loyalitas.create');
    }

    // Simpan data pelanggan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nama_Pelanggan' => 'required|string',
            'NoTelp_Pelanggan' => 'required|string|unique:pelanggan,NoTelp_Pelanggan',
        ]);

        // 1. Generate ID unik untuk loyalitas
        $idLoyalitas = 'LOYAL-' . strtoupper(bin2hex(random_bytes(5)));

        // 2. Generate ID_Pelanggan manual (jika DB bukan auto-increment)
        $idPelanggan = rand(1000, 9999); // bisa diganti UUID jika mau

        // 3. Buat data pelanggan dulu
        Pelanggan::create([
            'ID_Pelanggan' => $idPelanggan, // wajib
            'Nama_Pelanggan' => $validated['Nama_Pelanggan'],
            'NoTelp_Pelanggan' => $validated['NoTelp_Pelanggan'],
            'ID_Loyalitas' => $idLoyalitas,
        ]);

        // 4. Buat data loyalitas
        Loyalitas::create([
            'ID_Loyalitas' => $idLoyalitas,
            'ID_Pelanggan' => $idPelanggan,
            'ID_Transaksi' => 0, // harus ada
            'Nama_Pelanggan' => $validated['Nama_Pelanggan'],
            'NoTelp_Pelanggan' => $validated['NoTelp_Pelanggan'],
            'Jumlah_Transaksi' => 0,
        ]);


        return redirect()->route('loyalitas.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }


    // Hapus pelanggan
    public function destroy($id)
    {
        $pelanggan = Pelanggan::where('ID_Pelanggan', $id)->firstOrFail();
        Loyalitas::where('ID_Loyalitas', $pelanggan->ID_Loyalitas)->delete();
        $pelanggan->delete();
        return redirect()->route('loyalitas.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }

    // Fitur search realtime
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $pelanggans = Pelanggan::where('Nama_Pelanggan', 'like', "%{$query}%")
            ->orWhere('NoTelp_Pelanggan', 'like', "%{$query}%")
            ->orWhere('ID_Loyalitas', 'like', "%{$query}%")
            ->get();

        $output = '';
        foreach ($pelanggans as $index => $p) {
            $output .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . $p->Nama_Pelanggan . '</td>
                <td>' . $p->NoTelp_Pelanggan . '</td>
                <td>' . $p->ID_Loyalitas . '</td>
                <td>
                    <a href="' . route('loyalitas.show', $p->id) . '" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>';
        }

        if ($pelanggans->isEmpty()) {
            $output = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data ditemukan.</td></tr>';
        }

        return response($output);
    }

    // Detail pelanggan
    public function show($idLoyalitas)
    {
        $pelanggan = Pelanggan::with(['transactions' => function($query) {
            // (Opsional) Kita urutkan transaksi dari yang terbaru
            $query->orderBy('waktu_transaksi', 'desc'); 
        }])
        ->where('ID_Loyalitas', $idLoyalitas)
        ->firstOrFail();

        $idDiskonTerpakai = DB::table('penggunaan_diskon')
            ->where('ID_Loyalitas', $pelanggan->ID_Loyalitas)
            ->pluck('id_diskon')
            ->toArray();

        $diskonTersedia = Diskon::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->whereNotIn('id_diskon', $idDiskonTerpakai)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('loyalitas.show', [
            'pelanggan' => $pelanggan,
            'diskonTersedia' => $diskonTersedia, // atau variabel lain yang kamu punya
        ]);
    }

    public function gunakan(Request $request, $id)
    {
        $request->validate([
            'diskon_id' => 'required',
        ]);

        // PERBAIKAN DISINI:
        // Gunakan where() untuk mencari berdasarkan kolom 'ID_Loyalitas'
        // firstOrFail() akan return 404 jika data tidak ditemukan (sama fungsinya, tapi kolomnya benar)
        $pelanggan = Pelanggan::where('ID_Loyalitas', $id)->firstOrFail();

        // CEK jika pelanggan sudah pernah pakai diskon ini
        // Pastikan nama kolom di database 'penggunaan_diskon' sesuai (apakah 'ID_Loyalitas' atau 'id_loyalitas')
        $sudah = PenggunaanDiskon::where('ID_Loyalitas', $pelanggan->ID_Loyalitas)
            ->where('id_diskon', $request->diskon_id)
            ->first();

        if ($sudah) {
            return back()->with('error', 'Diskon ini sudah pernah digunakan.');
        }

        // SIMPAN
        PenggunaanDiskon::create([
            'id_diskon'     => $request->diskon_id,
            'ID_Loyalitas'  => $pelanggan->ID_Loyalitas, // Pastikan konsisten huruf besar/kecil dengan database
            'tanggal_pakai' => now(),
        ]);

        return back()->with('success', 'Diskon berhasil digunakan.');
    }

    // Form edit pelanggan
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('loyalitas.edit', compact('pelanggan'));
    }


    // Update pelanggan
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Update tabel pelanggan
        $pelanggan->update([
            'Nama_Pelanggan'    => $request->Nama_Pelanggan,
            'NoTelp_Pelanggan'  => $request->NoTelp_Pelanggan,
        ]);

        // Update tabel loyalitas yang berhubungan
        if ($pelanggan->ID_Loyalitas) {
            \App\Models\Loyalitas::where('ID_Loyalitas', $pelanggan->ID_Loyalitas)
                ->update([
                    'Nama_Pelanggan'    => $request->Nama_Pelanggan,
                    'NoTelp_Pelanggan'  => $request->NoTelp_Pelanggan,
                ]);
        }
        return redirect()->route('loyalitas.index', $pelanggan->ID_Pelanggan)
            ->with('success', 'Data berhasil diupdate.');
    }


    // Tambah transaksi + update total_transaksi otomatis
    public function tambahTransaksi(Request $request, $id)
    {
        $request->validate([
            'paket' => 'required|string|max:100',
            'harga' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $pelanggan = Pelanggan::where('ID_Pelanggan', $id)->firstOrFail();
        // Buat transaksi
        Transaksi::create([
        // Masukkan ID Pelanggan ke kolom 'pelanggan'
        'pelanggan'       => $pelanggan->ID_Pelanggan, 
        
        'nomor_nota'      => 'TRX-' . time(), // Contoh generate nota
        'paket'           => $request->paket,
        'harga'           => $request->harga, // Ini mungkin 'total' di database kamu
        'total'           => $request->harga, // Pastikan map ke kolom 'total'
        'waktu_transaksi' => now(),
    ]);

        // Update total transaksi di tabel loyalitas
        $pelanggan = Pelanggan::findOrFail($id);
        $loyalitas = Loyalitas::where('ID_Loyalitas', $pelanggan->ID_Loyalitas)->first();

        if ($loyalitas) {
            $loyalitas->increment('total_transaksi');
        }

        return redirect()
            ->route('loyalitas.show', $id)
            ->with('success', 'Transaksi berhasil ditambahkan dan total transaksi diperbarui!');
    }
    public function importXlsx(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $path = $request->file('file')->getPathname();

        $zip = new \ZipArchive;
        if ($zip->open($path) === TRUE) {

            // Ambil sheet data
            $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
            $shared = $zip->getFromName('xl/sharedStrings.xml');

            $zip->close();

            // Convert shared strings
            $sharedStrings = [];
            if ($shared) {
                $sharedXML = simplexml_load_string($shared);
                foreach ($sharedXML->si as $item) {
                    $sharedStrings[] = (string)$item->t;
                }
            }

            // Convert sheet rows
            $sheetXML = simplexml_load_string($xml);
            $rows = $sheetXML->sheetData->row;

            $first = true;

            foreach ($rows as $row) {

                // Skip header
                if ($first) {
                    $first = false;
                    continue;
                }

                $cells = [];
                foreach ($row->c as $c) {
                    $value = (string)$c->v;

                    // Jika cell pake shared string (s="1")
                    if (isset($c['t']) && $c['t'] == 's') {
                        $value = $sharedStrings[(int)$value] ?? $value;
                    }

                    $cells[] = $value;
                }

                // Pastikan tidak kosong
                if (count($cells) < 2) continue;

                $Nama_Pelanggan = $cells[0];
                $NoTelp_Pelanggan = $cells[1];

                // AUTO generate loyalitas ID
                $ID_Loyalitas = 'LOYAL-' . strtoupper(bin2hex(random_bytes(5)));

                Loyalitas::create([
                    'ID_Loyalitas' => $ID_Loyalitas,
                    'Nama_Pelanggan' => $Nama_Pelanggan,
                    'NoTelp_Pelanggan' => $NoTelp_Pelanggan,
                    'total_transaksi' => 0,
                ]);

                Pelanggan::create([
                    'Nama_Pelanggan' => $Nama_Pelanggan,
                    'NoTelp_Pelanggan' => $NoTelp_Pelanggan,
                    'ID_Loyalitas' => $ID_Loyalitas,
                ]);
            }

            return back()->with('success', 'Import XLSX berhasil!');
        }

        return back()->with('error', 'Gagal membaca file XLSX!');
    }
}
