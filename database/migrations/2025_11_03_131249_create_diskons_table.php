<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('diskons', function (Blueprint $table) {
            $table->id('id_diskon'); // Primary key
            $table->string('nama_diskon'); // Nama diskon
            $table->integer('jumlah_potongan'); // Jumlah potongan dalam rupiah
            $table->date('tanggal_mulai'); // Tanggal mulai
            $table->date('tanggal_selesai'); // Tanggal selesai
            $table->timestamps(); // created_at dan updated_at
            $table->integer('minimal_transaksi')->nullable(); // null artinya tanpa syarat
        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
