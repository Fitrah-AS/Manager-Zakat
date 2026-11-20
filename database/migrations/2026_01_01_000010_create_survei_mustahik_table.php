<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survei_mustahik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mustahik_id')
                ->constrained('mustahik')
                ->cascadeOnDelete();
            $table->foreignId('petugas_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->date('tanggal_survei')->index();
            $table->string('kondisi_ekonomi', 255);
            $table->unsignedSmallInteger('jumlah_tanggungan')->default(0);
            $table->enum('hasil_survei', ['layak', 'tidak_layak', 'perlu_tinjauan'])->index();
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_mustahik');
    }
};
