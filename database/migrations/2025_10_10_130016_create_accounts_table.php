<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_akun')->unique();
            $table->string('nama_akun');
            $table->enum('jenis_akun', ['aset lancar', 'aset tetap', 'liabilitas', 'ekuitas', 'pendapatan', 'beban']);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->enum('saldo_normal', ['debit', 'kredit']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
