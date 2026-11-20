@extends('layouts.app')

@section('title', 'Detail & Survei Mustahik - Sistem Informasi Zakat')
@section('page_title', 'Detail & Survei Kelayakan Mustahik')

@section('content')
<div class="mb-4">
    <a href="{{ route('mustahik.index') }}" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Mustahik</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kolom Kiri: Profil & Form Input Survei --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3 mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">{{ $mustahik->nama_lengkap }}</h2>
                    <p class="text-xs text-slate-500">Asnaf: {{ $mustahik->asnaf->nama_asnaf ?? '-' }}</p>
                </div>
                <x-badge-status :status="$mustahik->status_kelayakan" />
            </div>

            <dl class="space-y-2.5 text-sm">
                <div>
                    <dt class="text-xs text-slate-500">NIK</dt>
                    <dd class="font-medium text-slate-800">{{ $mustahik->nik ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Nomor HP</dt>
                    <dd class="font-medium text-slate-800">{{ $mustahik->no_hp }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Alamat</dt>
                    <dd class="text-slate-700">{{ $mustahik->alamat }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-slate-500">Koordinat Lokasi</dt>
                    <dd class="text-slate-700">
                        @if ($mustahik->latitude && $mustahik->longitude)
                            <span>{{ $mustahik->latitude }}, {{ $mustahik->longitude }}</span>
                            <a
                                href="https://maps.google.com/?q={{ $mustahik->latitude }},{{ $mustahik->longitude }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="block text-xs font-semibold text-[#1B4D3E] hover:underline mt-0.5"
                            >
                                Buka di Google Maps &rarr;
                            </a>
                        @else
                            <span class="text-slate-400">Belum dicatat</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Form Input Survei Mustahik --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs">
            <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Catat Survei Lapangan</h3>
            <form method="POST" action="{{ route('mustahik.survei.store', $mustahik) }}" data-loading-form class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Survei</label>
                    <input type="date" name="tanggal_survei" value="{{ now()->format('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi Ekonomi</label>
                    <input type="text" name="kondisi_ekonomi" placeholder="Contoh: Pekerja harian tidak tetap / lansia" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jml. Tanggungan</label>
                        <input type="number" name="jumlah_tanggungan" min="0" value="0" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hasil Survei</label>
                        <select name="hasil_survei" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="layak">Layak</option>
                            <option value="tidak_layak">Tidak Layak</option>
                            <option value="perlu_tinjauan">Perlu Tinjauan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Petugas</label>
                    <textarea name="catatan_petugas" rows="3" placeholder="Ringkasan hasil kunjungan lapangan..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>
                <button type="submit" data-loading-text="Menyimpan..." class="w-full rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-bold text-white cursor-pointer">
                    <span data-submit-label>Simpan Hasil Survei</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Kolom Kanan: Riwayat Survei & Riwayat Bantuan --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Riwayat Survei Mustahik</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($mustahik->survei as $sv)
                    <div class="p-5">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="text-sm font-semibold text-slate-900">
                                Survei {{ $sv->tanggal_survei?->format('d/m/Y') }}
                                <span class="text-xs font-normal text-slate-500">oleh {{ $sv->petugas->name ?? '-' }}</span>
                            </div>
                            <x-badge-status :status="$sv->hasil_survei" />
                        </div>
                        <p class="text-xs text-slate-700 mt-2"><span class="font-semibold">Kondisi Ekonomi:</span> {{ $sv->kondisi_ekonomi }} (Tanggungan: {{ $sv->jumlah_tanggungan }} jiwa)</p>
                        @if ($sv->catatan_petugas)
                            <p class="text-xs text-slate-600 mt-1 italic">"{{ $sv->catatan_petugas }}"</p>
                        @endif
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-400">Belum ada catatan survei untuk mustahik ini.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Riwayat Penerimaan Penyaluran Zakat</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                            <th class="px-5 py-3 font-semibold">Jenis Zakat</th>
                            <th class="px-5 py-3 font-semibold">Bentuk &amp; Jumlah</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mustahik->penyaluranDetails as $pd)
                            <tr>
                                <td class="px-5 py-3 text-xs">
                                    <div class="font-semibold text-slate-900">{{ $pd->penyaluran->kode_penyaluran ?? '-' }}</div>
                                    <div class="text-slate-500">{{ $pd->penyaluran->tanggal_penyaluran?->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-5 py-3 text-xs text-slate-700">{{ $pd->jenisZakat->nama_jenis ?? '-' }}</td>
                                <td class="px-5 py-3 text-xs font-semibold text-slate-900">
                                    @if ($pd->bentuk_salur->value === 'uang')
                                        Rp {{ number_format($pd->nominal_uang, 0, ',', '.') }}
                                    @else
                                        {{ number_format($pd->jumlah_beras_kg, 2, ',', '.') }} kg beras
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <x-badge-status :status="$pd->penyaluran->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-400">Belum pernah menerima penyaluran zakat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
