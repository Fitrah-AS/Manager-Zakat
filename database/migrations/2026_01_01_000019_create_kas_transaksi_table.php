<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kas_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->constrained('periode')
                ->restrictOnDelete();
            $table->dateTime('tanggal_transaksi')->index();
            $table->enum('jenis_arus', ['masuk', 'keluar'])->index();
            $table->enum('kategori', ['penerimaan_zakat', 'penyaluran_zakat', 'penyesuaian'])->index();
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_setelah', 15, 2);
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
        Schema::dropIfExists('kas_transaksi');
    }
};
