<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mustahik', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150)->index();
            $table->char('nik', 16)->nullable()->index();
            $table->text('alamat');
            $table->string('no_hp', 25)->index();
            $table->foreignId('asnaf_id')
                ->constrained('asnaf')
                ->restrictOnDelete();
            $table->enum('status_kelayakan', ['menunggu_survei', 'layak', 'tidak_layak'])
                ->default('menunggu_survei')
                ->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mustahik');
    }
};
