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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel orders (pakai kode_pesan)
            $table->string('kode_pesan');
            $table->foreign('kode_pesan')->references('kode_pesan')->on('orders')->onDelete('cascade');

            // Relasi ke tabel services (pakai kode_jasa)
            $table->string('kode_jasa');
            $table->foreign('kode_jasa')->references('kode_jasa')->on('services')->onDelete('cascade');

            // Relasi ke tabel materials (pakai kode_bahan)
            $table->string('kode_bahan');
            $table->foreign('kode_bahan')->references('kode_bahan')->on('materials')->onDelete('cascade');
            $table->decimal('ukuran_panjang', 10, 2);
            $table->decimal('ukuran_lebar', 10, 2);
            $table->integer('jumlah_pesan');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
