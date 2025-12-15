<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::dropIfExists('penggunaan_diskon');
}

public function down()
{
    Schema::create('penggunaan_diskon', function (Blueprint $table) {
        $table->id();
        $table->string('ID_Loyalitas');
        $table->string('id_diskon');
        $table->date('tanggal')->nullable();
        $table->timestamps();
    });
}


};
