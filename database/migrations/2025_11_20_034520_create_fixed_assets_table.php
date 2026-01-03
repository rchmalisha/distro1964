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
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_aset')->unique();
            $table->string('nama_aset');
            $table->string('kategori_aset');
            $table->date('tanggal_perolehan')->nullable();
            $table->decimal('harga_perolehan', 15, 2);
            $table->string('supplier')->nullable();
            $table->decimal('nilai_residu', 15, 2)->default(0);
            $table->integer('umur_ekonomis');
            $table->string('metode_penyusutan');
            $table->date('tanggal_jual')->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('pembeli')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
