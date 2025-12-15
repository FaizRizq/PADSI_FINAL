<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        
        // 1. Cek apakah user sudah login
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. LOGIKA UTAMA: Cek Parameter Middleware di Route (cek_role:1,2)
        // Jika di web.php Anda pakai ->middleware(CekRole::class . ':1,2')
        // Maka kode ini yang akan jalan (Paling Prioritas)
        if (!empty($roles)) {
            if (in_array($user->ID_Role, $roles)) {
                return $next($request);
            }
            
            // Jika ditolak, lempar ke halaman yang aman berdasarkan role mereka
            // if ($user->ID_Role == 3) {
            //     return redirect()->route('trends.index')->with('error', 'Akses ditolak.');
            // }
            // return redirect()->route('transactions.index')->with('error', 'Akses ditolak.');
            abort(403, 'Akses Ditolak. Anda tidak diizinkan masuk ke area ini!');
        }

        // 3. LOGIKA FALLBACK: Array Permission Manual
        // (Hanya jalan jika middleware dipanggil tanpa parameter)
        $roleId = $user->ID_Role; 
        
        $rolePermissions = [
            // Gunakan nama folder depannya saja (segment 1)
            
            // ID 1 (Manajer): Boleh akses folder transactions, loyalitas, trends
            1 => ['transactions', 'loyalitas', 'trends'], 
            
            // ID 2 (Pemilik): Boleh akses folder transactions, loyalitas, trends
            2 => ['transactions', 'loyalitas', 'trends'],
            
            // ID 3 (Kasir): HANYA boleh loyalitas dan trends (TIDAK ADA transactions)
            3 => ['loyalitas', 'trends'], 
        ];

        $currentSegment = $request->segment(1); // Mengambil 'transactions', 'trends', dll

        // Cek apakah segmen URL saat ini ada di daftar izin
        if (!in_array($currentSegment, $rolePermissions[$roleId] ?? [])) {
            
            // Jika akses ditolak, jangan lempar ke login (karena user sudah login).
            // Lempar ke "Halaman Utama" masing-masing role.
            
            // if ($roleId == 3) {
            //     // Kasir ditendang ke Trends
            //     return redirect()->route('trends.index')
            //         ->with('error', 'Anda tidak memiliki izin akses ke halaman tersebut.');
            // } else {
            //     // Manajer/Pemilik ditendang ke Transaksi
            //     return redirect()->route('transactions.index')
            //         ->with('error', 'Anda tidak memiliki izin akses ke halaman tersebut.');
            // }
            abort(403, 'Akses Terlarang: Anda tidak memiliki izin untuk mengakses halaman ' . ucfirst($currentSegment) . '.');
        }

        return $next($request);
    }
}
