<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Gunakan huruf kecil 'pelanggan' agar sinkron dengan query seeder
    Schema::create('pelanggan', function (Blueprint $table) {
        
        // PK: ID_Pelanggan
        $table->id('ID_Pelanggan');

        // Kolom ID_Loyalitas (Sudah benar tanpa ->change())
        $table->string('ID_Loyalitas', 50)->nullable();
        
        // Atribut lainnya
        $table->string('Nama_Pelanggan');
        $table->string('NoTelp_Pelanggan')->nullable();
        
        // Pastikan kolom ini juga ada jika seeder memanggilnya di tabel ini
        $table->integer('Jumlah_Transaksi')->default(0); 

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Pelanggan');
    }
};