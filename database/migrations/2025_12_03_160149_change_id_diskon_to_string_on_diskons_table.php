<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('diskons', function (Blueprint $table) {
            $table->string('id_diskon', 10)->change();
        });
    }

    public function down(): void {
        Schema::table('diskons', function (Blueprint $table) {
            $table->integer('id_diskon')->change();
        });
    }
};
