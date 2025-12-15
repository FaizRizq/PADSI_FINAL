<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrenPenjualan extends Model
{
    use HasFactory;

    /**
     * 1. Memberi tahu Laravel nama tabel yang benar.
     * (Standarnya, Laravel akan mencari 'tren_penjualans').
     */
    protected $table = 'Tren_Penjualan';

    /**
     * 2. Memberi tahu Laravel nama Primary Key yang benar.
     * (Standarnya, Laravel akan mencari 'id').
     */
    protected $primaryKey = 'ID_Tren';

    /**
     * 3. Kolom mana saja yang boleh diisi (Mass Assignment).
     */
    protected $fillable = [
        'ID_Transaksi',
        'ID_Laporan',
        'Produk_Terlaris',
        'Total_Penjualan',
        'Total_Transaksi',
        'Frekuensi_Transaksi',
    ];

    /**
     * 4. (SANGAT DIREKOMENDASIKAN) Definisikan Relasi
     */

    /**
     * Relasi "belongsTo": Setiap data TrenPenjualan DIMILIKI OLEH satu Transaction.
     */
    public function transaction()
    {
        // 'ID_Transaksi' adalah foreign key di tabel ini
        // 'id' adalah primary key di tabel 'transactions'
        return $this->belongsTo(Transaction::class, 'ID_Transaksi', 'id');
    }

    /**
     * (Opsional) Relasi ke Laporan
     */
    // public function laporan()
    // {
    //     return $this->belongsTo(Laporan::class, 'ID_Laporan');
    // }
}