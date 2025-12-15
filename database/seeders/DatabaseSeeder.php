<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Anda yang lain (jika ada)
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);

        // TAMBAHKAN BARIS INI:
        $this->call(PelangganLoyalitasSeeder::class); 
    }
}