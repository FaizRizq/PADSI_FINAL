<?php

namespace App\Providers;

use App\Models\User; // Menggunakan App\Models\User atau App\Models\Pengguna
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// Mengganti App\Models\User dengan nama Model Anda, jika menggunakan Pengguna
use App\Models\Pengguna;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Daftarkan Policy berbasis objek (jika Anda ingin menggunakan UserPolicy)
        // Pengguna::class => UserPolicy::class, 
    ]; 

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- PENTING: GATE::BEFORE INI DIHAPUS ---
        // Menghapus Gate::before(function) karena tujuannya adalah menonaktifkan otorisasi.
        // Dengan menghapusnya, semua Gate di bawah akan berfungsi.


        // === GATES DARI USE CASE DIAGRAM (UCD) ANDA, DENGAN PENYESUAIAN ROLE ===

        /**
         * Use Case: Tambah Data Transaksi Mentah
         * Aktor: Kasir
         */
        Gate::define('tambah-transaksi', function (Pengguna $pengguna) {
            // Role ID 3 = Kasir
            return $pengguna->ID_Role === 3; 
        });

        /**
         * Use Case: Tampil Transaksi Harian & Bulanan
         * Aktor: Kasir, Manajer, Pemilik (Semua Role)
         */
        Gate::define('tampil-transaksi-harian', function (Pengguna $pengguna) {
            // Karena semua Role diizinkan, kita hanya perlu cek apakah pengguna terotentikasi
            return true;
        });

        /**
         * Use Case: Filter Laporan
         * Aktor: Kasir, Manajer, Pemilik (Semua Role)
         */
        Gate::define('filter-laporan', function (Pengguna $pengguna) {
             // Karena semua Role diizinkan
            return true;
        });

        /**
         * Use Case: Tampil Laporan Loyalitas Pelanggan
         * Aktor: Manajer, Pemilik
         */
        Gate::define('lihat-loyalitas', function (Pengguna $pengguna) {
            // Role ID 1 = Manajer, Role ID 2 = Pemilik
            $authRoleId = $pengguna->ID_Role;
            return in_array($authRoleId, [1, 2]);
        });

        /**
         * Use Case: Tren Penjualan
         * Aktor: Manajer, Pemilik
         */
        Gate::define('lihat-tren-penjualan', function (Pengguna $pengguna) {
            // Role ID 1 = Manajer, Role ID 2 = Pemilik
            $authRoleId = $pengguna->ID_Role;
            return in_array($authRoleId, [1, 2]);
        });

        /**
         * Use Case: Ekspor Laporan
         * Aktor: Pemilik
         */
        Gate::define('ekspor-laporan', function (Pengguna $pengguna) {
            // Role ID 2 = Pemilik
            return $pengguna->ID_Role === 2;
        });

        // === GATES DARI DISKUSI KITA SEBELUMNYA (IMPOR FILE) ===

        /**
         * Fitur: Impor Laporan (Upload file)
         * Aktor: Manajer
         */
        Gate::define('impor-laporan', function (Pengguna $pengguna) {
            // Role ID 1 = Manajer
            return $pengguna->ID_Role === 1;
        });

        /**
         * Fitur: Lihat Halaman Laporan (Daftar file yg diimpor)
         * Aktor: Manajer, Kasir, Pemilik (Semua Role)
         */
        Gate::define('lihat-laporan', function (Pengguna $pengguna) {
            // Karena semua Role diizinkan
            return true;
        });
    }
}