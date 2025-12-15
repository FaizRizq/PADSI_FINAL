<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengguna; // <-- Pastikan model Pengguna di-import
use App\Models\Role;     // <-- Asumsi Anda punya model Role

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama Admin.
     * Ini adalah method yang dipanggil rute 'admin.dashboard'.
     */
    public function index()
    {
        // Contoh: Ambil data ringkasan untuk dashboard admin
        $totalUsers = Pengguna::count();
        $totalRoles = Role::count(); // Asumsi ada model Role

        // Tampilkan view
        // file: /resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', compact('totalUsers', 'totalRoles'));
    }

    /**
     * Menampilkan halaman untuk mengelola daftar pengguna.
     * Ini dipanggil oleh rute 'admin.users.list'.
     */
    public function listUsers()
    {
        // Ambil semua pengguna (selain Super Admin sendiri)
        $users = Pengguna::where('ID_Role', '!=', 1)
                         ->with('role') // Asumsi ada relasi 'role' di model Pengguna
                         ->get();

        // file: /resources/views/admin/manage_users.blade.php
        return view('admin.manage_users', compact('users'));
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Ini dipanggil oleh rute 'admin.users.edit'.
     */
    public function editUserForm($id)
    {
        $user = Pengguna::findOrFail($id);
        $roles = Role::all(); // Ambil semua role untuk <select> dropdown

        // file: /resources/views/admin/edit_user.blade.php
        return view('admin.edit_user', compact('user', 'roles'));
    }

    /**
     * Memproses data dari form edit pengguna.
     * Ini dipanggil oleh rute 'admin.users.update'.
     */
    public function updateUser(Request $request, $id)
    {
        $user = Pengguna::findOrFail($id);

        // Validasi data (contoh sederhana)
        $request->validate([
            'Nama_Pengguna' => 'required|string|max:255',
            'ID_Role' => 'required|integer|exists:roles,ID_Role',
        ]);

        // Update data
        $user->Nama_Pengguna = $request->Nama_Pengguna;
        $user->ID_Role = $request->ID_Role;
        // Tambahkan field lain jika perlu
        $user->save();

        return redirect()->route('admin.users.list')->with('success', 'Data pengguna berhasil diupdate.');
    }
}