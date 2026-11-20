<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique();
            $table->string('nama_metode', 100);
            $table->enum('tipe', ['tunai', 'transfer_bank', 'qris', 'lainnya'])->index();
            $table->string('nama_bank', 100)->nullable();
            $table->string('no_rekening', 60)->nullable();
            $table->string('atas_nama', 120)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};
