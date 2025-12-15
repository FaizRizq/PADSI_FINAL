<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException; // <--- Tambahkan Baris Ini Penting!

class Authenticate extends Middleware
{
    /**
     * Dapatkan path ke mana pengguna harus diarahkan 
     * jika mereka tidak diautentikasi.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Kembalikan ke default saja (biar rapi), karena kita akan mencegatnya di fungsi bawah.
        return $request->expectsJson() ? null : route('login');
    }

    /**
     * --- TAMBAHKAN FUNGSI INI ---
     * Fungsi ini menimpa perilaku asli Laravel saat user belum login.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Cek jika request minta JSON (API), return json error
        if ($request->expectsJson()) {
            throw new AuthenticationException(
                'Unauthenticated.', $guards, $this->redirectTo($request)
            );
        }

        // JIKA BUKAN API (WEB BIASA), LANGSUNG ABORT 403
        abort(403, 'Akses Ditolak. Silakan Login terlebih dahulu untuk masuk!');
    }
}