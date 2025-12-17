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
        // Membuat tabel 'Loyalitas' sesuai ERD
        Schema::create('Loyalitas', function (Blueprint $table) {
            
            // PK: ID_Loyalitas
            $table->id('ID_Loyalitas');

            // FK: ID_Pelanggan
            // Asumsi me-refer ke 'ID_Pelanggan' di tabel 'pelanggans'
            $table->unsignedBigInteger('ID_Pelanggan');

            // FK: ID_Transaksi
            // Asumsi me-refer ke 'ID_Transaksi' di tabel 'transaksis'
            $table->unsignedBigInteger('ID_Transaksi');

            // Atribut lainnya
            $table->string('Nama_Pelanggan');
            $table->string('NoTelp_Pelanggan')->nullable(); // Nomor HP sebaiknya string
            $table->integer('Jumlah_Transaksi')->default(0); // Untuk menghitung jumlah
            $table->decimal('Jumlah_Diskon', 15, 2)->default(0); // 'decimal' terbaik untuk mata uang

            // Standard Laravel timestamps
            $table->timestamps();

            // === Definisi Foreign Key ===
            // (Baris ini bisa di-uncomment jika Anda sudah punya tabel `pelanggans` dan `transaksis`)

            // $table->foreign('ID_Pelanggan')->references('ID_Pelanggan')->on('pelanggans')->onDelete('cascade');
            // $table->foreign('ID_Transaksi')->references('ID_Transaksi')->on('transaksis')->onDelete('set null');
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