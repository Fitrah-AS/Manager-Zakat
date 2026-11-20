@extends('layouts.app')

@section('title', 'Periode & Tarif Zakat Fitrah - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Periode & Tarif Zakat Fitrah')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Periode Zakat</h2>
            <p class="text-xs text-slate-500">Kelola tahun Hijriah, masa berlaku, batas akhir zakat fitrah, serta tarif beras &amp; uang per jiwa.</p>
        </div>
        <button
            type="button"
            data-modal-open="modal-tambah-periode"
            class="inline-flex items-center justify-center rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer"
        >
            + Tambah Periode
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Periode &amp; Tahun Hijriah</th>
                    <th class="px-5 py-3 font-semibold">Rentang Tanggal</th>
                    <th class="px-5 py-3 font-semibold">Tarif Zakat Fitrah / Jiwa</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($periodes as $item)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $item->nama_periode }}</div>
                            <div class="text-xs text-slate-500">{{ $item->tahun_hijriah }} ({{ $item->tahun_masehi }} M)</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div>{{ $item->tanggal_mulai?->format('d/m/Y') }} s.d. {{ $item->tanggal_selesai?->format('d/m/Y') }}</div>
                            @if ($item->batas_akhir_fitrah)
                                <div class="text-amber-700 font-medium mt-0.5">Batas Fitrah: {{ $item->batas_akhir_fitrah->format('d/m/Y H:i') }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            @if ($item->tarifFitrah)
                                <div class="font-semibold text-slate-900">{{ number_format($item->tarifFitrah->beras_per_jiwa_kg, 2, ',', '.') }} kg beras</div>
                                <div class="text-emerald-800 font-semibold">Rp {{ number_format($item->tarifFitrah->nominal_uang, 0, ',', '.') }}</div>
                            @else
                                <span class="text-red-600">Tarif belum diatur</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($item->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-[#0F5132]">Periode Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            @if (! $item->is_active)
                                <form method="POST" action="{{ route('admin.periode.activate', $item) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-3 py-1.5 text-xs font-bold text-slate-950 cursor-pointer">
                                        Aktifkan
                                    </button>
                                </form>
                            @endif
                            <button
                                type="button"
                                data-modal-open="modal-edit-periode-{{ $item->id }}"
                                class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer"
                            >
                                Edit
                            </button>
                            @if (! $item->is_active && ! $item->sudahDigunakan())
                                <button
                                    type="button"
                                    data-confirm-delete
                                    data-action="{{ route('admin.periode.destroy', $item) }}"
                                    data-title="Hapus Periode"
                                    data-message="Yakin ingin menghapus periode {{ $item->nama_periode }}?"
                                    class="rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                                >
                                    Hapus
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Edit Periode --}}
                    <div id="modal-edit-periode-{{ $item->id }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Periode &amp; Tarif Fitrah</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="{{ route('admin.periode.update', $item) }}" data-loading-form class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Periode</label>
                                        <input type="text" name="nama_periode" value="{{ $item->nama_periode }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Hijriah</label>
                                        <input type="text" name="tahun_hijriah" value="{{ $item->tahun_hijriah }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Masehi</label>
                                        <input type="text" name="tahun_masehi" value="{{ $item->tahun_masehi }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" value="{{ $item->tanggal_mulai?->format('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                                        <input type="date" name="tanggal_selesai" value="{{ $item->tanggal_selesai?->format('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Akhir Fitrah</label>
                                        <input type="datetime-local" name="batas_akhir_fitrah" value="{{ $item->batas_akhir_fitrah?->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Beras / Jiwa (Kg)</label>
                                        <input type="number" step="0.01" name="beras_per_jiwa_kg" value="{{ $item->tarifFitrah->beras_per_jiwa_kg ?? 2.50 }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang / Jiwa (Rp)</label>
                                        <input type="number" step="100" name="nominal_uang" value="{{ $item->tarifFitrah->nominal_uang ?? 45000 }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Tarif</label>
                                    <input type="text" name="keterangan_tarif" value="{{ $item->tarifFitrah->keterangan ?? '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active) class="rounded border-slate-300 text-[#1B4D3E]">
                                    <span>Jadikan Periode Aktif</span>
                                </label>
                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                                    <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                                        <span data-submit-label>Simpan Perubahan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada periode zakat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $periodes->links() }}</div>
</div>

{{-- Modal Tambah Periode --}}
<div id="modal-tambah-periode" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Periode &amp; Tarif Fitrah Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('admin.periode.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Periode</label>
                    <input type="text" name="nama_periode" placeholder="Contoh: Ramadhan 1448 H / 2027 M" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Hijriah</label>
                    <input type="text" name="tahun_hijriah" placeholder="1448 H" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Masehi</label>
                    <input type="text" name="tahun_masehi" placeholder="2027" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Batas Akhir Fitrah</label>
                    <input type="datetime-local" name="batas_akhir_fitrah" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Beras / Jiwa (Kg)</label>
                    <input type="number" step="0.01" name="beras_per_jiwa_kg" value="2.50" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal Uang / Jiwa (Rp)</label>
                    <input type="number" step="100" name="nominal_uang" value="45000" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Tarif</label>
                <input type="text" name="keterangan_tarif" placeholder="Dasar penetapan tarif fitrah" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-[#1B4D3E]">
                <span>Langsung jadikan periode aktif</span>
            </label>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    <span data-submit-label>Simpan Periode</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
