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
    Schema::create('general_journal_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('general_journal_id');
        $table->string('kode_akun');
        $table->decimal('debit', 15, 2)->default(0);
        $table->decimal('kredit', 15, 2)->default(0);
        $table->foreign('general_journal_id')->references('id')->on('general_journals')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_journal_details');
    }
};
