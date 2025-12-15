<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $table = 'transactions';

    protected $fillable = [
        'waktu_transaksi',
        'nomor_nota',
        'pelanggan',
        'diskon',
        'pajak',
        'total',
        'tipe_bayar',
    ];

    /**
     * Pastikan 'waktu_transaksi' diperlakukan sebagai objek Tanggal (Carbon).
     *
     * @var array
     */
    protected $casts = [
        'waktu_transaksi' => 'datetime',
    ];

    public function dataPelanggan()
    {
        // arg 2: foreign key di tabel transactions (kolom 'pelanggan')
        // arg 3: primary key di tabel pelanggan (kolom 'ID_Pelanggan')
        return $this->belongsTo(Pelanggan::class, 'pelanggan', 'ID_Loyalitas');
    }
}