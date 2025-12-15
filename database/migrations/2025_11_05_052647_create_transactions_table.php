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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id(); // INI SUDAH OTOMATIS JADI NOMOR URUT UTAMA

        // HILANG: $table->integer('nomor')->nullable();
        
        $table->datetime('waktu_transaksi'); 
        $table->string('nomor_nota')->unique();
        $table->string('pelanggan')->nullable();
        $table->decimal('diskon', 15, 2)->default(0);
        $table->decimal('pajak', 15, 2)->default(0);
        $table->decimal('total', 15, 2);
        $table->string('tipe_bayar');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
