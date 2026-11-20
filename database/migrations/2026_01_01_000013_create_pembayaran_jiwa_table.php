<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_jiwa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_detail_id')
                ->constrained('pembayaran_detail')
                ->cascadeOnDelete();
            $table->string('nama_anggota', 150);
            $table->string('hubungan_keluarga', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_jiwa');
    }
};
