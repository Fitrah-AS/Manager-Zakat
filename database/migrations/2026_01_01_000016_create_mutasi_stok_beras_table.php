<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_stok_beras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->constrained('periode')
                ->restrictOnDelete();
            $table->dateTime('tanggal_mutasi')->index();
            $table->enum('tipe', ['masuk', 'keluar', 'penyesuaian'])->index();
            $table->decimal('jumlah_kg', 10, 2);
            $table->decimal('saldo_sebelum_kg', 10, 2);
            $table->decimal('saldo_sesudah_kg', 10, 2);
            $table->string('referensi_type', 120)->nullable();
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();

            $table->index(['referensi_type', 'referensi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok_beras');
    }
};
