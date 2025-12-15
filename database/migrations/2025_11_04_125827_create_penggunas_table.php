<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id('ID_Pengguna'); // Sesuai ERD
            
            // Foreign Key ke tabel Roles
            $table->foreignId('ID_Role')->constrained(
                table: 'roles', column: 'ID_Role'
            ); 
            
            $table->string('Nama_Pengguna');
            $table->string('Alamat_Pengguna')->nullable();
            $table->string('NoTelp_Pengguna')->nullable();
            $table->string('Alamat_Email_Pengguna')->unique();
            $table->string('Password_Pengguna');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('penggunas');
    }
};