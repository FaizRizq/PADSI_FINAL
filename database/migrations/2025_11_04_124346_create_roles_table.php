<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('ID_Role'); // Sesuai ERD
            $table->string('Nama_Role');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('roles');
    }
};  