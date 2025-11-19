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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesan')->unique();
            $table->foreignId('pelanggan_id')->constrained('customers')->onDelete('cascade');
            $table->date('tgl_pesan');
            $table->date('tgl_ambil');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('biaya_lainnya', 15, 2)->nullable();
            $table->decimal('potongan_harga', 15, 2)->nullable();
            $table->decimal('total_akhir', 15, 2);
            $table->string('upload_file')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
