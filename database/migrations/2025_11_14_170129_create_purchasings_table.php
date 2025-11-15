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
        Schema::create('purchasings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan');
            $table->string('nama_bahan');
            $table->decimal('ukuran_meter', 10, 2);
            $table->date('tanggal_pembelian')->nullable();
            $table->string('pemasok')->nullable();
            $table->decimal('harga_per_meter', 12, 2)->nullable();
            $table->decimal('total_harga', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasings');
    }
};
