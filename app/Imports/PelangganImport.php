<?php
namespace App\Imports;

use App\Models\Pelanggan;
use App\Models\Loyalitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Agar membaca header excel

class PelangganImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Asumsi header excel: nama_pelanggan, no_telp
        
        $idLoyalitas = 'LOYAL-' . strtoupper(bin2hex(random_bytes(5)));
        $idPelanggan = rand(1000, 9999); // Saran: Hati-hati duplikat, lebih aman pakai auto-increment DB

        // Create Loyalitas
        Loyalitas::create([
            'ID_Loyalitas'     => $idLoyalitas,
            'Nama_Pelanggan'   => $row['nama_pelanggan'], // sesuaikan header excel
            'NoTelp_Pelanggan' => $row['no_telp'],        // sesuaikan header excel
            'ID_Pelanggan'     => $idPelanggan,
            'ID_Transaksi'     => 0,
            'Jumlah_Transaksi' => 0,
        ]);

        // Return Pelanggan untuk disimpan otomatis
        return new Pelanggan([
            'ID_Pelanggan'     => $idPelanggan,
            'Nama_Pelanggan'   => $row['nama_pelanggan'],
            'NoTelp_Pelanggan' => $row['no_telp'],
            'ID_Loyalitas'     => $idLoyalitas,
        ]);
    }
}