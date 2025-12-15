<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\Loyalitas;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';
    protected $primaryKey = 'ID_Pelanggan';
    // public $incrementing = false; // kalau pakai manual ID
    protected $keyType = 'string'; // kalau pakai LOYAL-xxxxx atau manual string

    protected $fillable = [
        'ID_Pelanggan', 'Nama_Pelanggan', 'NoTelp_Pelanggan', 'ID_Loyalitas'
    ];

    // Relasi ke Loyalitas
    public function loyalitas()
    {
        // Parameter: (ModelTujuan, Foreign Key di tabel tujuan, Primary Key di tabel ini)
        return $this->hasOne(Loyalitas::class, 'ID_Pelanggan', 'ID_Pelanggan');
    }

    // Relasi ke Transaksi (jika ada tabel transaksi)
    public function transactions()
    {
       return $this->hasMany(Transaction::class, 'pelanggan', 'ID_Loyalitas');
    }
}
