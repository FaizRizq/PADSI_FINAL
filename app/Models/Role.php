<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;
    protected $primaryKey = 'ID_Role'; // Penting!
    protected $fillable = ['Nama_Role'];

    // Relasi: 1 Role punya BANYAK Pengguna
    public function penggunas(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'ID_Role', 'ID_Role');
    }
}