@extends('layouts.public')

@section('title', 'Kalkulator Zakat Mal - Sistem Informasi Zakat')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6">
        <a href="{{ route('beranda') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Beranda</a>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-2">Kalkulator Zakat Mal</h1>
        <p class="text-sm text-slate-600 mt-1">Hitung perkiraan zakat harta Anda berdasarkan jenis harta, standar nisab aktif, dan ketentuan haul.</p>
    </div>

    {{-- Disclaimer Penting --}}
    <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50 px-5 py-4 text-xs sm:text-sm text-amber-950">
        <p class="font-bold">Perhatian:</p>
        <p class="mt-0.5">Kalkulator ini hanya sebagai alat bantu perhitungan administratif berdasarkan standar umum nisab &amp; haul, <strong>bukan fatwa agama</strong>. Konsultasikan kasus khusus harta Anda kepada ulama atau dewan syariah setempat.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs">
            <form id="public-kalkulator-form" action="{{ route('kalkulator.hitung') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="calc-jenis-harta" class="block text-xs font-semibold text-slate-700 mb-1.5">Jenis Harta Zakat Mal *</label>
                    <select id="calc-jenis-harta" name="jenis_harta" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm font-medium focus:border-[#1B4D3E] focus:outline-none">
                        <option value="emas_perak">Zakat Emas &amp; Perak (Nisab 85 gr emas, Haul 1 th)</option>
                        <option value="penghasilan">Zakat Penghasilan / Profesi (Bruto / Neto)</option>
                        <option value="perdagangan">Zakat Perdagangan (Aset Lancar - Hutang)</option>
                        <option value="tabungan_investasi">Zakat Tabungan &amp; Investasi</option>
                        <option value="pertanian">Zakat Pertanian (Nisab 653 kg gabah / 520 kg beras)</option>
                    </select>
                </div>

                <div id="calc-info-ketentuan" class="rounded-lg bg-emerald-50 border border-emerald-200 px-3.5 py-2.5 text-xs text-emerald-900"></div>

                <div id="calc-panel-penghasilan" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Perhitungan</label>
                        <select name="metode_penghasilan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="neto">Neto (Setelah Kebutuhan Pokok/Hutang)</option>
                            <option value="bruto">Bruto (Penghasilan Kotor)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Periode Penghasilan</label>
                        <select name="periode_penghasilan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="bulanan">Bulanan (Nisab Tahunan / 12)</option>
                            <option value="tahunan">Tahunan (Nisab 85 gr Emas)</option>
                        </select>
                    </div>
                </div>

                <div id="calc-panel-emas">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Referensi Harga Emas per Gram (Rp)</label>
                    <input type="number" step="1" min="1" name="harga_emas_per_gram" value="{{ (int) $hargaEmasDefault }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                    <p class="text-[11px] text-slate-500 mt-1">Default mengikuti standar nisab aktif lembaga (Rp {{ number_format($hargaEmasDefault, 0, ',', '.') }}/gr).</p>
                </div>

                <div id="calc-panel-umum" class="space-y-4">
                    <div>
                        <label id="calc-label-harta" class="block text-xs font-semibold text-slate-700 mb-1">Total Nilai Harta (Rp)</label>
                        <input type="number" step="0.01" min="0" name="nilai_harta_kotor" placeholder="0" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label id="calc-label-hutang" class="block text-xs font-semibold text-slate-700 mb-1">Hutang Jatuh Tempo (Rp)</label>
                        <input type="number" step="0.01" min="0" name="hutang_jatuh_tempo" value="0" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                    </div>
                </div>

                <div id="calc-panel-pertanian" class="hidden space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Bentuk Hasil Panen</label>
                            <select name="jenis_panen" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="gabah">Gabah Kering (Nisab 653 kg)</option>
                                <option value="beras">Beras (Nisab 520 kg)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Sistem Irigasi / Pengairan</label>
                            <select name="jenis_irigasi" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                <option value="berbiaya">Irigasi Berbiaya (Kadar 5%)</option>
                                <option value="alami">Air Hujan / Alami (Kadar 10%)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Berat Hasil Panen (Kg)</label>
                            <input type="number" step="0.01" min="0" name="berat_panen_kg" placeholder="0" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga per Kg (Rp, Opsional)</label>
                            <input type="number" step="1" min="0" name="harga_per_kg" value="0" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                        </div>
                    </div>
                </div>

                <div id="calc-panel-haul" class="flex items-center gap-2 pt-1">
                    <input id="calc-sudah-haul" type="checkbox" name="sudah_haul" value="1" checked class="rounded border-slate-300 text-[#1B4D3E]">
                    <label for="calc-sudah-haul" class="text-xs font-medium text-slate-700">Harta sudah mencapai haul (dimiliki penuh selama 1 tahun Hijriah)</label>
                </div>

                <div id="calc-error-box" class="hidden rounded-lg bg-red-50 border border-red-200 px-3.5 py-2.5 text-xs text-red-700"></div>

                <button type="submit" class="w-full rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] py-3 text-sm font-bold text-white shadow transition cursor-pointer">
                    Hitung Zakat Mal
                </button>
            </form>
        </div>

        {{-- Panel Hasil --}}
        <div class="lg:col-span-5 space-y-4">
            <div id="calc-result-box" class="hidden bg-white rounded-2xl border-2 border-[#1B4D3E] p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900">Hasil Perhitungan</h2>
                    <span id="calc-result-status" class="inline-flex rounded-lg px-3 py-1 text-xs font-bold"></span>
                </div>

                <div class="rounded-xl bg-[#F4F6F5] p-4 border border-slate-200">
                    <p class="text-xs text-slate-500">Estimasi Zakat yang Ditunaikan</p>
                    <p id="calc-result-nominal" class="text-2xl font-bold text-[#1B4D3E] mt-1">Rp 0</p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-slate-500">Harta Bersih / Panen</p>
                        <p id="calc-result-bersih" class="font-bold text-slate-900 mt-0.5">-</p>
                    </div>
                    <div class="rounded-lg border border-slate-200 p-3">
                        <p class="text-slate-500">Batas Nisab</p>
                        <p id="calc-result-nisab" class="font-bold text-slate-900 mt-0.5">-</p>
                    </div>
                </div>

                <div class="text-xs text-slate-600 space-y-1">
                    <p><span class="font-semibold">Kadar Zakat:</span> <span id="calc-result-kadar">2.5%</span></p>
                    <p id="calc-result-catatan" class="leading-relaxed"></p>
                </div>

                <p class="text-[11px] text-amber-800 bg-amber-50 border border-amber-200 rounded-lg p-3">
                    Catatan: Hasil ini merupakan alat bantu hitung administratif, bukan fatwa agama. Untuk menyalurkan zakat, silakan hubungi petugas amil di konter lembaga.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-3 text-xs text-slate-600">
                <h3 class="text-sm font-bold text-slate-900">Ringkasan Ketentuan Setiap Jenis Harta</h3>
                <ul class="space-y-2 list-disc pl-4">
                    <li><strong>Emas &amp; Perak:</strong> Nisab 85 gr emas, syarat haul 1 tahun, kadar 2,5%.</li>
                    <li><strong>Penghasilan:</strong> Nisab setara 85 gr emas/tahun (atau dibagi 12 untuk bulanan), kadar 2,5%.</li>
                    <li><strong>Perdagangan:</strong> Aset lancar dagang dikurangi hutang jangka pendek, syarat nisab 85 gr emas &amp; haul, kadar 2,5%.</li>
                    <li><strong>Tabungan/Investasi:</strong> Saldo bersih mencapai 85 gr emas &amp; haul 1 tahun, kadar 2,5%.</li>
                    <li><strong>Pertanian:</strong> Dihitung saat panen (tanpa haul), nisab 653 kg gabah atau 520 kg beras, kadar 5% atau 10%.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
