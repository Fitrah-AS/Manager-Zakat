<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyaluran_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyaluran_id')
                ->constrained('penyaluran')
                ->cascadeOnDelete();
            $table->foreignId('mustahik_id')
                ->constrained('mustahik')
                ->restrictOnDelete();
            $table->foreignId('asnaf_id')
                ->constrained('asnaf')
                ->restrictOnDelete();
            $table->foreignId('jenis_zakat_id')
                ->constrained('jenis_zakat')
                ->restrictOnDelete();
            $table->enum('bentuk_salur', ['uang', 'beras'])->index();
            $table->decimal('nominal_uang', 15, 2)->default(0);
            $table->decimal('jumlah_beras_kg', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyaluran_detail');
    }
};
