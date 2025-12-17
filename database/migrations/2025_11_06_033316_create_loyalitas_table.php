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
    // Ubah 'Loyalitas' menjadi 'loyalitas' (huruf kecil)
    Schema::create('loyalitas', function (Blueprint $table) {
        
        // PK: ID_Loyalitas (Ini sudah benar secara kode, akan jadi Auto-Increment)
        $table->id('ID_Loyalitas');

        // FK: ID_Pelanggan
        $table->unsignedBigInteger('ID_Pelanggan');

        // FK: ID_Transaksi
        $table->unsignedBigInteger('ID_Transaksi');

        // Atribut lainnya
        $table->string('Nama_Pelanggan');
        $table->string('NoTelp_Pelanggan')->nullable();
        $table->integer('Jumlah_Transaksi')->default(0);
        $table->decimal('Jumlah_Diskon', 15, 2)->default(0);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Loyalitas');
    }
};