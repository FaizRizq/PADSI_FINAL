<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaanDiskon extends Model
{
    protected $table = 'penggunaan_diskon';
    protected $fillable = ['id_diskon', 'ID_Loyalitas', 'tanggal_pakai'];
}
