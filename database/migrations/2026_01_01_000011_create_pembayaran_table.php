<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->foreignId('periode_id')
                ->constrained('periode')
                ->restrictOnDelete();
            $table->foreignId('muzakki_id')
                ->constrained('muzakki')
                ->restrictOnDelete();
            $table->foreignId('metode_pembayaran_id')
                ->constrained('metode_pembayaran')
                ->restrictOnDelete();
            $table->foreignId('petugas_input_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('tanggal_bayar')->index();
            $table->decimal('total_uang', 15, 2)->default(0);
            $table->decimal('total_beras_kg', 10, 2)->default(0);
            $table->string('bukti_transfer', 255)->nullable();
            $table->enum('status', ['menunggu_verifikasi', 'terverifikasi', 'ditolak'])
                ->default('menunggu_verifikasi')
                ->index();
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
