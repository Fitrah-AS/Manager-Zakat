<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')
                ->constrained('pembayaran')
                ->cascadeOnDelete();
            $table->foreignId('jenis_zakat_id')
                ->constrained('jenis_zakat')
                ->restrictOnDelete();
            $table->enum('bentuk_bayar', ['uang', 'beras'])->index();
            $table->unsignedInteger('jumlah_jiwa')->nullable();
            $table->decimal('nominal_uang', 15, 2)->default(0);
            $table->decimal('jumlah_beras_kg', 10, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_detail');
    }
};
