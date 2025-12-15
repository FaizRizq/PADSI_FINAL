<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel 'transaksi'
 *
 * Catatan: Kolom 'id_laporan' (FK) telah dihilangkan
 * untuk memperbaiki dependensi sirkular pada ERD awal.
 */
class Transaksi extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Primary Key untuk model ini.
     *
     * @var string
     */
    protected $primaryKey = 'ID_Transaksi'; // Sesuaikan jika nama PK Anda berbeda

    /**
     * Menunjukkan apakah model harus diberi stempel waktu.
     *
     * @var bool
     */
    public $timestamps = false; // Asumsi tidak ada kolom created_at/updated_at

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ID_Pelanggan',
        'ID_Loyalitas',
        'Tipe_Bayar',
        'Jenis_Transaksi',
        'Tanggal_Transaksi',
        'Total_Harga',
        'Total_Pajak',
        'Status_Transaksi',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Tanggal_Transaksi' => 'datetime',
        'Total_Harga' => 'decimal:2', // Asumsi total harga adalah desimal
        'Total_Pajak' => 'decimal:2', // Asumsi total pajak adalah desimal
    ];

    // Definisikan relasi di sini jika perlu
    // Contoh:
    // public function pelanggan()
    // {
    //     return $this->belongsTo(Pelanggan::class, 'ID_Pelanggan');
    // }
    //
    // public function loyalitas()
    // {
    //     return $this->belongsTo(Loyalitas::class, 'ID_Loyalitas');
    // }
}
