<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';
    protected $primaryKey = 'ID_Laporan';

    protected $fillable = [
        'ID_Pengguna',
        'Nama_File_Asli',
        'Tanggal_Impor',
    ];

    /**
     * Relasi: Laporan ini DIIMPOR OLEH satu Pengguna
     */
    public function pengimpor(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'ID_Pengguna', 'ID_Pengguna');
    }

    /**
     * Relasi: Laporan ini MEMILIKI BANYAK Transaksi
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'ID_Laporan', 'ID_Laporan');
    }
}
