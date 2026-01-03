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
        Schema::create('general_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('kode_akun'); // relasi ke accounts
            $table->decimal('nominal', 15, 2);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('kode_akun')
                ->references('kode_akun')
                ->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_transactions');
    }
};
