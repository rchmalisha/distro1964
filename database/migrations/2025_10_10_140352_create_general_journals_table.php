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
    Schema::create('general_journals', function (Blueprint $table) {
        $table->id();
        $table->string('kode_jurnal')->unique();
        $table->string('no_bukti')->nullable();
        $table->date('tanggal_jurnal');
        $table->text('keterangan_jurnal')->nullable();
        $table->string('ref_tipe')->nullable();
        $table->unsignedBigInteger('ref_id')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journals');
    }
};
