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
        // Membuat tabel 'Pelanggan' sesuai ERD
        Schema::create('Pelanggan', function (Blueprint $table) {
            
            // PK: ID_Pelanggan
            $table->id('ID_Pelanggan');

            // FK: ID_Loyalitas
            // Asumsi relasi 1-ke-1 dengan tabel 'Loyalitas'
            // Kita buat 'nullable' dan 'unique' agar tidak error
            // Atribut lainnya
            $table->string('ID_Loyalitas', 50)->nullable();
            $table->string('Nama_Pelanggan');
            $table->string('NoTelp_Pelanggan')->nullable(); // Nomor HP sebaiknya string
            $table->integer('Jumlah_Transaksi')->default(0); // Atau tipe data yang sesuai
            

            // Standard Laravel timestamps
            $table->timestamps();

            // === Definisi Foreign Key ===
            // (Bisa di-uncomment jika tabel 'Loyalitas' sudah ada SEBELUM ini)

            // $table->foreign('ID_Loyalitas')
            //       ->references('ID_Loyalitas')
            //       ->on('Loyalitas')
            //       ->onDelete('set null'); // Jika data loyalitas dihapus, FK di sini jadi null
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