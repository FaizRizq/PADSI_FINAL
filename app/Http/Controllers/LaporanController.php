<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Penting untuk Transaction
use Illuminate\Support\Facades\Log; // <-- Penting untuk error handling

class LaporanController extends Controller
{
    /**
     * Menampilkan daftar semua laporan (batch impor).
     * Semua role (Manajer, Kasir, Pemilik) bisa akses ini.
     */
    public function index()
    {
        // Ambil semua laporan, urutkan dari yg terbaru
        $laporans = Laporan::with('pengimpor') // Ambil data 'pengimpor' (relasi)
                           ->orderBy('Tanggal_Impor', 'desc')
                           ->paginate(20);

        return view('dashboard.laporan', compact('laporans'));
    }

    /**
     * Menampilkan form untuk upload file.
     * Hanya Manajer yang bisa akses ini.
     */
    public function create()
    {
        return view('dashboard.laporan.impor');
    }

    /**
     * Menyimpan (Store) file impor ke database.
     * Hanya Manajer yang bisa akses ini.
     */
    public function store(Request $request)
    {
        // 1. Validasi: Pastikan ada file dan itu file csv/txt
        $request->validate([
            'file_laporan' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_laporan');
        $namaFileAsli = $file->getClientOriginalName();
        $pathFile = $file->getRealPath();

        // 2. Gunakan DB Transaction
        // Ini memastikan jika ada 1 baris gagal, semua dibatalkan (rollback)
        DB::beginTransaction();

        try {
            // 3. Buat Laporan (Induk) terlebih dahulu
            $laporan = Laporan::create([
                'ID_Pengguna' => Auth::id(), // ID Manajer yg sedang login
                'Nama_File_Asli' => $namaFileAsli,
                'Tanggal_Impor' => now(),
            ]);

            // 4. Buka dan Baca file CSV
            // (Ini adalah cara PHP native. Untuk file Excel/.xlsx,
            //  disarankan memakai library 'Maatwebsite/Excel')
            
            $handle = fopen($pathFile, 'r');
            
            // Lewati baris header (jika ada)
            fgetcsv($handle); 
            
            $dataTransaksi = [];
            $now = now();

            // 5. Looping setiap baris di file CSV
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Asumsi urutan kolom di CSV:
                // [0] = ID Pelanggan, [1] = Tipe Bayar, [2] = Tgl Transaksi, [3] = Total Harga ...
                
                $dataTransaksi[] = [
                    'ID_Laporan' => $laporan->ID_Laporan, // <-- Link ke Induk
                    'ID_Pelanggan' => $row[0] ?? null,
                    'ID_Loyalitas' => $row[1] ?? null,
                    'Tipe_Bayar' => $row[2] ?? 'N/A',
                    'Jenis_Transaksi' => $row[3] ?? 'N/A',
                    'Tanggal_Transaksi' => $row[4] ? \Carbon\Carbon::parse($row[4]) : $now,
                    'Total_Harga' => $row[5] ?? 0,
                    'Total_Pajak' => $row[6] ?? 0,
                    'Status_Transaksi' => $row[7] ?? 'Completed',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            fclose($handle);

            // 6. Masukkan semua data transaksi sekaligus (Bulk Insert)
            // Ini jauh lebih cepat daripada create() satu per satu
            if (!empty($dataTransaksi)) {
                Transaksi::insert($dataTransaksi);
            }

            // 7. Jika semua berhasil, 'commit' perubahan ke database
            DB::commit();

            return redirect()->route('laporan.index')
                             ->with('success', "Impor file '$namaFileAsli' berhasil.");

        } catch (\Exception $e) {
            // 8. Jika ada error, batalkan semua (rollback)
            DB::rollBack();
            
            // Catat error untuk developer
            Log::error("Gagal impor file: " . $e->getMessage()); 

            // Kirim pesan error ke Manajer
            return back()->with('error', 'Terjadi kesalahan saat impor file. Data dibatalkan.');
        }
    }

    /**
     * Menampilkan detail satu laporan (semua transaksinya).
     * Semua role (Manajer, Kasir, Pemilik) bisa akses ini.
     */
    public function show(Laporan $laporan)
    {
        // $laporan sudah otomatis didapat dari URL (Route Model Binding)
        // Kita ambil semua transaksi yang terkait dengannya
        $transaksis = $laporan->transaksis()->paginate(50);

        return view('dashboard.laporan.show', compact('laporan', 'transaksis'));
    }
}
