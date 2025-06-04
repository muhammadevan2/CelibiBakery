<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('pesanans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pelanggan');
        $table->string('no_hp');
        $table->string('no_meja');
        $table->string('bukti_pembayaran')->nullable();
        $table->enum('status', ['pending', 'confirm', 'delivery'])->default('pending');
        $table->integer('total_harga');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
