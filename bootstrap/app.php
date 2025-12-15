<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException; // <-- Pastikan import ini ada

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ... konfigurasi middleware lain ...
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // --- LOGIKA UTAMA SKENARIO 1 ---
        // Menangani user yang BELUM LOGIN (AuthenticationException)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            
            // Jika request API, biarkan return JSON
            if ($request->expectsJson()) {
                return null; 
            }

            // JIKA WEB BIASA:
            // Daripada redirect ke login, kita TAMPILKAN 403
            abort(403, 'Eits! Anda belum login. Silakan login terlebih dahulu untuk mengakses sistem.');
        });
        
    })->create();