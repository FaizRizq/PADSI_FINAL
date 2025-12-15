<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Ganti "return new class extends Migration" menjadi baris di bawah ini:
class CreatePenggunaanDiskonTable extends Migration
{
    public function up()
{
    // TAMBAHKAN BARIS INI: Hapus tabel jika sudah ada
    Schema::dropIfExists('penggunaan_diskons'); 

    // Baru buat ulang
    Schema::create('penggunaan_diskon', function (Blueprint $table) {
        $table->id();
        $table->string('ID_Loyalitas');
        $table->string('id_diskon');
        $table->timestamp('tanggal_pakai')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_diskons');
    }
} // Hapus tanda titik koma (;) di akhir jika sebelumnya ada