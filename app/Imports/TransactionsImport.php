<?php

namespace App\Imports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class TransactionsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // **PENTING**:
        // Nama-nama di dalam $row[] (contoh: 'id_pelanggan')
        // HARUS SAMA PERSIS dengan nama header kolom di file Excel/CSV Anda.
        // Sesuaikan string berikut dengan file Anda.

        return new Transaksi([
            'ID_Pelanggan'      => $row['id_pelanggan'],
            'ID_Loyalitas'      => $row['id_loyalitas'],
            'Tipe_Bayar'        => $row['tipe_bayar'],
            'Jenis_Transaksi'   => $row['jenis_transaksi'],
            'Tanggal_Transaksi' => Date::excelToDateTimeObject($row['tanggal_transaksi']), // Konversi tanggal Excel
            'Total_Harga'       => $row['total_harga'],
            'Total_Pajak'       => $row['total_pajak'],
            'Status_Transaksi'  => $row['status_transaksi'],
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Validasi bahwa header 'id_pelanggan' ada dan tidak kosong
            'id_pelanggan' => 'required|integer',

            // Validasi bahwa header 'id_loyalitas' ada dan tidak kosong
            'id_loyalitas' => 'required|integer',

            // 'tanggal_transaksi' harus ada dan merupakan format tanggal yang valid
            'tanggal_transaksi' => 'required',

            // 'total_harga' harus ada dan merupakan angka
            'total_harga' => 'required|numeric',

            // 'total_pajak' bisa kosong (nullable) tapi jika ada harus angka
            'total_pajak' => 'nullable|numeric',

            // 'status_transaksi' harus ada
            'status_transaksi' => 'required|string',
        ];
    }
}
