<?php

namespace App\Imports;

use App\Models\Transaction;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon; // <-- 1. Import Carbon untuk memproses tanggal

class TransactionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   public function model(array $row)
    {
        // 2. Gabungkan string tanggal dan waktu dari Excel
        $stringGabungan = $row['tanggal'] . ' ' . $row['waktu'];

        // 3. BUAT TANGGAL DARI FORMAT SPESIFIK: Hari/Bulan/Tahun Jam:Menit:Detik
        // Ini adalah baris yang kita ubah
        $waktuTransaksi = Carbon::createFromFormat('d/m/Y H:i:s', $stringGabungan);

        // 4. Buat model Transaction baru...
        return new Transaction([
            'waktu_transaksi' => $waktuTransaksi, // Masukkan data yang sudah diparsing
            'nomor_nota'      => $row['nomor_nota'],
            'pelanggan'       => $row['pelanggan'],
            'diskon'          => $row['diskon'],
            'pajak'           => $row['pajak'],
            'total'           => $row['total'],
            'tipe_bayar'      => $row['tipe_bayar'],
        ]);
    }
}