<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- Pastikan ini 'Authenticatable'
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Notifications\Notifiable; // Hapus jika tidak pakai

class Pengguna extends Authenticatable
{
    use HasFactory; //, Notifiable; // Hapus Notifiable jika tidak pakai

    protected $table = 'penggunas';
    protected $primaryKey = 'ID_Pengguna';

    protected $fillable = [
        'ID_Role',
        'Nama_Pengguna',
        'Alamat_Pengguna',
        'NoTelp_Pengguna',
        'Alamat_Email_Pengguna',
        'Password_Pengguna',
    ];

    protected $hidden = [
        'Password_Pengguna',
        'remember_token', // Tambahkan ini untuk keamanan
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Hapus jika tidak pakai verifikasi email
    ];

    // Relasi ke Role (dari jawaban sebelumnya)
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'ID_Role', 'ID_Role');
    }
    
    // Otomatis Hash Password saat disimpan (dari jawaban sebelumnya)
    protected function passwordPengguna(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    // !! TAMBAHAN PALING PENTING !!
    // Ini memberitahu Laravel cara MENGAMBIL nama kolom password Anda
    // saat melakukan Auth::attempt()
    public function getAuthPassword()
    {
        return $this->Password_Pengguna;
    }
}

