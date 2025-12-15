<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
    Schema::create('penggunaan_diskon', function (Blueprint $table) {
        $table->id();
        $table->string('ID_Loyalitas');
        $table->string('id_diskon');
        $table->timestamp('tanggal_pakai')->nullable();
        $table->timestamps();
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('penggunaan_diskon');
    }
};
