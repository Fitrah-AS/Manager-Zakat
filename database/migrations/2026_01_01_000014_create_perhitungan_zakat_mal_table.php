<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perhitungan_zakat_mal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_detail_id')
                ->nullable()
                ->constrained('pembayaran_detail')
                ->cascadeOnDelete();
            $table->foreignId('nisab_id')
                ->nullable()
                ->constrained('nisab')
                ->nullOnDelete();
            $table->string('jenis_harta', 50)->index();
            $table->string('metode_hitung', 50)->nullable();
            $table->decimal('nilai_harta_kotor', 15, 2)->default(0);
            $table->decimal('hutang_jatuh_tempo', 15, 2)->default(0);
            $table->decimal('harta_bersih', 15, 2)->default(0);
            $table->decimal('nilai_nisab_saat_hitung', 15, 2)->default(0);
            $table->boolean('sudah_haul')->default(true);
            $table->boolean('memenuhi_nisab')->default(false);
            $table->decimal('persentase_zakat', 5, 2)->default(2.50);
            $table->decimal('hasil_zakat', 15, 2)->default(0);
            $table->text('catatan_perhitungan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perhitungan_zakat_mal');
    }
};
