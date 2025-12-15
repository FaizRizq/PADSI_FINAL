<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id('ID_Laporan'); // Primary Key

            // Foreign Key ke 'penggunas' (tabel pengguna Anda)
            // Mencatat SIAPA yang mengimpor
            $table->foreignId('ID_Pengguna')->constrained(
                table: 'penggunas', column: 'ID_Pengguna'
            );

            $table->string('Nama_File_Asli')->nullable();
            $table->timestamp('Tanggal_Impor');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
