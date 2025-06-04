<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['tunai', 'qris'])->default('tunai');
        });
    }

    public function down()
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};
