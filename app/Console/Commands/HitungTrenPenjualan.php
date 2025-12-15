<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\TrenPenjualan; // <-- Import model baru
use Illuminate\Support\Facades\DB;

class HitungTrenPenjualan extends Command
{
    protected $signature = 'app:hitung-tren-penjualan';
    protected $description = 'Menghitung data tren dari tabel transaksi dan menyimpannya ke tabel Tren_Penjualan';

    public function handle()
    {
        $this->info('Menghitung tren penjualan bulanan...');

        // 1. Ambil semua data agregat dari tabel transactions
        $dataAgregat = Transaction::query()
            ->select(
                DB::raw("DATE_FORMAT(waktu_transaksi, '%Y-%m') as bulan_tahun"),
                DB::raw('SUM(total) as total_rupiah'), // Ini akan jadi 'Total_Penjualan'
                DB::raw('COUNT(id) as total_frekuensi') // Ini akan jadi 'Frekuensi_Transaksi'
            )
            ->groupBy('bulan_tahun')
            ->orderBy('bulan_tahun', 'asc')
            ->get();

        // 2. Loop dan masukkan ke tabel Tren_Penjualan
        foreach ($dataAgregat as $data) {

            // Gunakan updateOrCreate() untuk menghindari duplikat
            // Ia akan mencari 'bulan_tahun', jika ada di-update, jika tidak ada di-create.
            TrenPenjualan::updateOrCreate(
                // Kunci pencarian:
                ['bulan_tahun' => $data->bulan_tahun], 

                // Data untuk di-update atau di-create:
                [
                    'Total_Penjualan' => $data->total_rupiah,
                    'Frekuensi_Transaksi' => $data->total_frekuensi,
                    // 'Produk_Terlaris' => ... (Anda bisa tambahkan logika ini nanti)
                ]
            );
        }

        $this->info('Selesai! Tabel Tren_Penjualan telah diperbarui.');

        

        return 0;
    }
}