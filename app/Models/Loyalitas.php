<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Loyalitas extends Model
{
    use HasFactory;

    // 1. Pastikan nama tabel benar
    protected $table = 'loyalitas'; // sesuaikan jika nama tabelmu beda (misal: loyalitas_pelanggan)

    // 2. WAJIB: Definisikan Primary Key (Penyebab utama data tidak ter-update)
    protected $primaryKey = 'ID_Loyalitas';

    // Karena ID-nya string (LOYAL-XXX), matikan auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // 3. WAJIB: Daftarkan kolom yang boleh di-update (Fillable)
    protected $fillable = [
        'ID_Loyalitas',
        'ID_Pelanggan',
        'ID_Transaksi',
        'Nama_Pelanggan',   // <--- Pastikan ini ada!
        'NoTelp_Pelanggan', // <--- Pastikan ini ada!
        'Jumlah_Transaksi',
        'total_transaksi'   // sesuaikan besar kecil huruf dengan database
    ];

    // Relasi ke pelanggan (Opsional, tapi bagus untuk ada)
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }
}
