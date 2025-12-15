<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('diskons', function (Blueprint $table) {
            if (!Schema::hasColumn('diskons', 'minimal_transaksi')) {
                $table->integer('minimal_transaksi')->unsigned()->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('diskons', function (Blueprint $table) {
            $table->dropColumn(['minimal_pembayaran', 'minimal_transaksi']);
        });
    }
};
