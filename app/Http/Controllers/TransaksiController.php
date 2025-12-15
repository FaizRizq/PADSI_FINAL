<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\TransactionsImport; // <-- Import kelas Impor
use Maatwebsite\Excel\Facades\Excel; // <-- Import fasad Excel
use Maatwebsite\Excel\Validators\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman form untuk impor.
     */
    public function showImportForm()
    {
        // Mengarahkan ke view blade yang berisi form upload
        return view('transaksi.import');
    }

    /**
     * Menangani file yang di-upload untuk diimpor.
     */
    public function handleImport(Request $request)
    {
        // 1. Validasi request
        $request->validate([
            'file_transaksi' => 'required|file|mimes:xlsx,xls,csv' // Hanya izinkan file excel/csv
        ]);

        try {
            // 2. Ambil file dari request
            $file = $request->file('file_transaksi');

            // 3. Lakukan impor menggunakan package Maatwebsite
            // Parameter pertama adalah file, parameter kedua adalah kelas Import kita
            Excel::import(new TransactionsImport, $file);

            // 4. Jika sukses, kembalikan ke halaman sebelumnya dengan pesan sukses
            return back()->with('success', 'Data transaksi berhasil diimpor!');

        } catch (ValidationException $e) {
            // Tangkap error validasi dari file Excel (didefinisikan di TransactionsImport.php)
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                 $errorMessages[] = "Baris ke-{$failure->row()}: " . implode(', ', $failure->errors());
            }
            // Kembalikan ke halaman sebelumnya dengan pesan error validasi
            return back()->with('error', 'Validasi gagal: ' . implode(' | ', $errorMessages));

        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
