<?php

namespace App\Policies;

use App\Models\Pengguna as User;
use Illuminate\Support\Arr; // Menggunakan Arr untuk kemudahan pengecekan array

class UserPolicy
{
    /**
     * Tentukan apakah pengguna yang terotentikasi (authUser) dapat melihat
     * daftar semua pengguna (index).
     */
    public function viewAny(User $authUser)
    {
        // Hanya ID 1 (Manajer) yang diizinkan melihat semua daftar pengguna.
        return $authUser->ID_Role === 1;
    }

    /**
     * Tentukan apakah pengguna yang terotentikasi (authUser) dapat melihat
     * profil pengguna target (targetUser) secara spesifik.
     */
    public function view(User $authUser, User $targetUser)
    {
        $authRoleId = $authUser->ID_Role;

        // 1. Otorisasi Manajer (ID 1) - Setara Super Admin
        if ($authRoleId === 1) {
            return true;
        }

        // 2. Otorisasi Pemilik (ID 2) - Asumsi setara Area Manager (berdasarkan lokasi/cabang)
        // Diizinkan jika ID Lokasi mereka sama.
        if ($authRoleId === 2) {
            return $authUser->location_id === $targetUser->location_id;
        }

        // 3. Otorisasi Kasir (ID 3) - Asumsi setara Hotel Manager/Front Desk (berdasarkan gedung/building)
        // Diizinkan jika ID Gedung (Building) mereka sama.
        if ($authRoleId === 3) {
            // Catatan: Jika Kasir tidak perlu melihat pengguna lain sama sekali,
            // Anda bisa menghapus blok ini. Saat ini diizinkan melihat pengguna di gedung yang sama.
            return $authUser->building_id === $targetUser->building_id;
        }

        return false;
    }

    /**
     * Tentukan apakah pengguna yang terotentikasi (authUser) dapat
     * membuat pengguna baru.
     */
    public function create(User $authUser)
    {
        // Hanya ID 1 (Manajer) yang diizinkan membuat pengguna.
        return $authUser->ID_Role === 1;
    }

    /**
     * Tentukan apakah pengguna yang terotentikasi (authUser) dapat
     * memperbarui (update) pengguna target (targetUser).
     * Izin UPDATE menggunakan logika VIEW.
     */
    public function update(User $authUser, User $targetUser)
    {
        // Memanggil logika view()
        return $this->view($authUser, $targetUser);
    }

    /**
     * Tentukan apakah pengguna yang terotentikasi (authUser) dapat
     * menghapus (delete) pengguna target (targetUser).
     */
    public function delete(User $authUser, User $targetUser)
    {
        // Hanya ID 1 (Manajer) yang diizinkan menghapus pengguna.
        return $authUser->ID_Role === 1;
    }
}