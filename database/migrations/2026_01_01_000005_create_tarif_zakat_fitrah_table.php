<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarif_zakat_fitrah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')
                ->unique()
                ->constrained('periode')
                ->restrictOnDelete();
            $table->decimal('beras_per_jiwa_kg', 8, 2);
            $table->decimal('nominal_uang', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_zakat_fitrah');
    }
};
