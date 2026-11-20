<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nisab', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->nullable()
                ->constrained('periode')
                ->nullOnDelete();
            $table->enum('jenis_harta', [
                'emas_perak',
                'penghasilan',
                'perdagangan',
                'tabungan_investasi',
                'pertanian',
            ])->index();
            $table->string('nama_standar', 150);
            $table->string('basis_nisab', 150);
            $table->decimal('harga_emas_per_gram', 15, 2)->default(0);
            $table->decimal('gram_emas_nisab', 8, 2)->default(85.00);
            $table->decimal('nisab_pertanian_kg', 10, 2)->nullable();
            $table->decimal('nilai_nisab_uang', 15, 2)->default(0);
            $table->decimal('kadar_zakat_persen', 5, 2)->default(2.50);
            $table->string('ketentuan_haul', 255);
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nisab');
    }
};
