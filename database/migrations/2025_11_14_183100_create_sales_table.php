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
        Schema::create('sales', function (Blueprint $table) {
            // Primary Key manual (string)
            $table->string('kode_jual')->primary();

            // Relasi ke orders (order punya id numeric)
            $table->string('kode_pesan');
            $table->foreign('kode_pesan')->references('kode_pesan')->on('orders')->onDelete('cascade');

            // Data penjualan
            $table->date('tgl_transaksi');
            $table->integer('total_akhir');
            $table->integer('bayar');
            $table->integer('kembalian')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
