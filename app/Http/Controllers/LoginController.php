<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
 
class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:dns'], // 'dns' adalah validasi yg bagus
            'password' => ['required'],
        ]);
 
        $authCredentials = [
            'Alamat_Email_Pengguna' => $credentials['email'],
            'password'              => $credentials['password'], 
            // Catatan: Key untuk password harus 'password' agar dibaca Auth::attempt
        ];

        if (Auth::attempt($authCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // --- LOGIKA REDIRECT KHUSUS BERDASARKAN ROLE ---
            // Kita ambil ID Role pengguna yang baru saja login
            $role = Auth::user()->ID_Role;

            // Skenario A: Jika Role 3 (Kasir), arahkan ke halaman Trends
            if ($role == 3) {
                return redirect()->route('trends.index');
            }

            // Skenario B: Jika Role 1 (Manajer) atau 2 (Pemilik), arahkan ke Transaksi
            // (Gunakan route yang spesifik, jangan gunakan intended() agar tidak nyasar)
            return redirect()->route('transactions.index');
        }

        // 4. Jika gagal, kembalikan error ke form input 'email'
        //    Ini sudah benar
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan salah.',
            'password' => 'Email atau Password yang Anda berikan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Arahkan kembali ke halaman login
    }
}

