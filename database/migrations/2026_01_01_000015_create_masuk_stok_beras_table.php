<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('masuk_stok_beras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->constrained('periode')
                ->restrictOnDelete();
            $table->foreignId('pembayaran_id')
                ->nullable()
                ->unique()
                ->constrained('pembayaran')
                ->restrictOnDelete();
            $table->date('tanggal_masuk')->index();
            $table->decimal('jumlah_kg', 10, 2);
            $table->enum('sumber', ['zakat_fitrah', 'zakat_mal', 'infak_beras', 'penyesuaian'])->index();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('masuk_stok_beras');
    }
};
