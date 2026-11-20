<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyaluran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penyaluran', 50)->unique();
            $table->foreignId('periode_id')
                ->constrained('periode')
                ->restrictOnDelete();
            $table->dateTime('tanggal_penyaluran')->index();
            $table->foreignId('petugas_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->enum('status', ['direncanakan', 'selesai', 'dibatalkan'])
                ->default('direncanakan')
                ->index();
            $table->dateTime('selesai_at')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyaluran');
    }
};
