<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $table = 'diskons';
    protected $primaryKey = 'id_diskon';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getKodeDiskonAttribute()
{
    return 'DSK' . str_pad($this->id_diskon, 6, '0', STR_PAD_LEFT);
}

    protected $fillable = [
        'id_diskon',
        'nama_diskon',
        'jumlah_potongan',
        'minimal_pembayaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'minimal_transaksi',
    ];
}
