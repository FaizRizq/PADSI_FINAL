<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan; 
use App\Models\Loyalitas; 
use App\Models\Transaction; 
use Illuminate\Support\Facades\DB; 

class PelangganLoyalitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan tabel Pelanggan & Loyalitas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pelanggan::truncate();
        Loyalitas::truncate();
        // Kita TIDAK truncate Transaction agar data DUMMY-001 tetap ada
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // --- LANGKAH 1: Cari atau Buat 1 Transaksi Dummy ---
        $transaksiDummy = Transaction::firstOrCreate(
            ['nomor_nota' => 'DUMMY-001'], // Kunci pencarian
            [
                'waktu_transaksi' => now(), // Data jika dibuat baru
                'total' => 50000,
                'tipe_bayar' => 'ShopeeFood',
                'pelanggan' => 'Pelanggan Dummy',
                'diskon' => 0,
                'pajak' => 0,
            ]
        );


        // --- LANGKAH 2: Data Dummy Pelanggan (Nama sudah Anda ubah) ---
        $dataPelanggan = [
            ['nama' => 'Evan Chan', 'no_telp' => '081234567890'],
            ['nama' => 'Robin Hut', 'no_telp' => '081211112222'],
            ['nama' => 'Dadang', 'no_telp' => '081233334444'],
            ['nama' => 'Erlan Batu Bara', 'no_telp' => '081255556666'],
            ['nama' => 'Faiz Indobears', 'no_telp' => '081277778888'],
        ];


        // --- LANGKAH 3: Looping & Buat Data (INI YANG DIUBAH) ---
        foreach ($dataPelanggan as $data) {
            
            $pelanggan = Pelanggan::create([
                'Nama_Pelanggan' => $data['nama'],
                'NoTelp_Pelanggan' => $data['no_telp'],
                'ID_Loyalitas' => null, 
            ]);


            // 1. Tentukan jumlah transaksi berdasarkan nama
            $jumlahTransaksi = 2; // Default untuk pelanggan lain
            
            if ($data['nama'] == 'Evan Chan') {
                $jumlahTransaksi = 5;
            } elseif ($data['nama'] == 'Faiz Indobears') {
                $jumlahTransaksi = 17;
            } elseif ($data['nama'] == 'Dadang') {
                $jumlahTransaksi = 8;
            }
          


            $loyalitas = Loyalitas::create([
                'ID_Pelanggan' => $pelanggan->ID_Pelanggan, 
                'ID_Transaksi' => $transaksiDummy->id, 
                'Nama_Pelanggan' => $pelanggan->Nama_Pelanggan,
                'NoTelp_Pelanggan' => $pelanggan->NoTelp_Pelanggan,
                'Jumlah_Transaksi' => $jumlahTransaksi, // <-- Menggunakan variabel baru
                'Jumlah_Diskon' => 0, // Anda bisa acak ini jika mau
            ]);

            $pelanggan->ID_Loyalitas = $loyalitas->ID_Loyalitas;
            $pelanggan->save();
        }
    }
}