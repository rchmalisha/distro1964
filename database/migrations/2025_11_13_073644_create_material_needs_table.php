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
        Schema::create('material_needs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesan');
            $table->foreign('kode_pesan')->references('kode_pesan')->on('orders')->onDelete('cascade');

            $table->string('kode_bahan');
            $table->foreign('kode_bahan')->references('kode_bahan')->on('materials')->onDelete('cascade');

            $table->string('jenis_jasa');
            $table->enum('jenis_bahan', ['dtf', 'polyflex']);
            $table->decimal('ukuran_panjang', 10, 2);
            $table->decimal('ukuran_lebar', 10, 2);
            $table->integer('jumlah_pesanan');
            $table->decimal('waste_persen', 5, 2)->default(10);
            $table->decimal('kebutuhan_bahan_meter', 10, 2);
            $table->date('tgl_pesan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_needs');
    }
};
