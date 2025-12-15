<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Tren_Penjualan', function (Blueprint $table) {
            
            $table->id('ID_Tren');
            $table->string('bulan_tahun')->unique(); 
            $table->string('Produk_Terlaris')->nullable();
            
            // DIUBAH: Kolom untuk Total Rupiah (SUM(total))
            $table->decimal('Total_Transaksi_Rupiah', 15, 2)->default(0); 
            
            // DIUBAH: Kolom untuk Jumlah Hitungan (COUNT(id))
            $table->integer('Frekuensi_Transaksi')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Tren_Penjualan');
    }
};