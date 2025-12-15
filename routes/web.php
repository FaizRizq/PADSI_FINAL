<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LoyalitasController;
use App\Http\Controllers\TrendController;

use App\Http\Middleware\CekRole; // 
use App\Http\Controllers\DashboardController; // Asumsi ada controller untuk dashboard default
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\PelangganController;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/

// Route untuk Login (tujuan redirect saat gagal otorisasi)
// Route::get('login', [LoginController::class, 'showLoginForm'])->name('auth.login');
// // Asumsi Anda punya route untuk proses login (post) dan logout di sini juga.

// // Route untuk Halaman Dashboard Default (Tujuan redirect, harus selalu bisa diakses)
// // Saya asumsikan segmen utamanya adalah 'dashboard'
// Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/', function () {
     return view('auth.login');
});
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Routes yang memerlukan Otorisasi CekRole)
|--------------------------------------------------------------------------
| Middleware CekRole akan dijalankan untuk semua route di dalam group ini.
*/

Route::middleware(['auth'])->group(function () {

     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

     Route::prefix('transactions')->group(function () {
          // 1. Halaman Index
          Route::get('index', [TransactionController::class, 'index'])
               ->middleware(CekRole::class . ':1,2')
               ->name('transactions.index');

          // 2. Halaman Form Import (GET)
          // Nama route ini HARUS 'transactions.import.form' agar cocok dengan Blade Anda
          Route::get('import', [TransactionController::class, 'showImportForm']) // Pastikan nama method di controller benar (showImportForm atau import)
               ->middleware(CekRole::class . ':1')
               ->name('transactions.import.form');

          // 3. Proses Import (POST)
          // Tambahkan ini juga agar form bisa disubmit
          Route::post('import', [TransactionController::class, 'import'])
               ->middleware(CekRole::class . ':1')
               ->name('transactions.import.submit');

          Route::get('export', [TransactionController::class, 'showExportForm'])
               ->middleware(CekRole::class . ':1,2')
               ->name('transactions.export.form');

          // 5. === BARU: Proses Generate PDF (POST) ===
          // Akses: Manajer (1) & Pemilik (2)
          Route::post('export', [TransactionController::class, 'exportPDF'])
               ->middleware(CekRole::class . ':1,2')
               ->name('transactions.export.submit');
     });

     // Route Lainnya...
     Route::get('loyalitas', [PelangganController::class, 'index'])
          ->middleware(CekRole::class . ':1,2,3')
          ->name('loyalitas.index');


     // FORM TAMBAH
     Route::get('/loyalitas/create', [PelangganController::class, 'create'])->name('loyalitas.create');

     // PROSES SIMPAN
     Route::post('/loyalitas/store', [PelangganController::class, 'store'])->name('loyalitas.store');


     Route::get('/loyalitas/search', [PelangganController::class, 'search'])->name('loyalitas.search');

     Route::get('loyalitas/{id}/edit', [PelangganController::class, 'edit'])->name('loyalitas.edit');
     Route::delete('loyalitas/{id}/destroy', [PelangganController::class, 'destroy'])->name('loyalitas.destroy');

     Route::get('/loyalitas/{id}', [PelangganController::class, 'show'])->name('loyalitas.show');



     Route::get('/loyalitas', [PelangganController::class, 'index'])->name('loyalitas.index');


     Route::get('/diskon', [DiskonController::class, 'index'])
          ->middleware(CekRole::class . ':1,2')
          ->name('diskon.index');

     Route::post('/loyalitas/{id}/pakai-diskon', [PelangganController::class, 'gunakan'])
          ->name('idDiskonTerpakai');

     Route::get('/diskon/create', [DiskonController::class, 'create'])
          ->middleware(CekRole::class . ':1,2')
          ->name('diskon.create');

     Route::resource('pelanggan', PelangganController::class);


     Route::put('/loyalitas/{id}', [PelangganController::class, 'update'])->name('loyalitas.update');

     Route::get('/diskon', [DiskonController::class, 'index'])->name('diskon.index')
          ->middleware(CekRole::class . ':1,2');
          
     Route::get('/diskon/{id}/edit', [DiskonController::class, 'edit'])->name('diskon.edit')
          ->middleware(CekRole::class . ':1,2');

     Route::put('/diskon/{id}', [DiskonController::class, 'update'])->name('diskon.update')
          ->middleware(CekRole::class . ':1,2');

     Route::resource('diskon', DiskonController::class)
          ->middleware(CekRole::class . ':1,2');

     Route::post('/diskon', [DiskonController::class, 'store'])
          ->middleware(CekRole::class . ':1,2')
          ->name('diskon.store');

     Route::get('trends', [TrendController::class, 'index'])
          ->middleware(CekRole::class . ':1,2,3')
          ->name('trends.index');
});
