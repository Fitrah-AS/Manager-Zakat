<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode', function (Blueprint $table) {
            $table->id();
            $table->string('nama_periode', 120);
            $table->string('tahun_masehi', 20);
            $table->string('tahun_hijriah', 30);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->dateTime('batas_akhir_fitrah')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode');
    }
};
