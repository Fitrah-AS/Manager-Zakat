@extends('layouts.app')

@section('title', 'Metode Pembayaran - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Metode Pembayaran')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Metode Pembayaran</h2>
            <p class="text-xs text-slate-500">Kanal tunai, rekening transfer bank, dan QRIS lembaga.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-metode" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer">
            + Tambah Metode
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Metode</th>
                    <th class="px-5 py-3 font-semibold">Tipe</th>
                    <th class="px-5 py-3 font-semibold">Rekening / Tujuan</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($metodes as $item)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $item->nama_metode }}</div>
                            <div class="text-xs text-slate-500">{{ $item->kode }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold uppercase text-slate-700">{{ str_replace('_', ' ', $item->tipe) }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            @if ($item->no_rekening)
                                <div class="font-semibold text-slate-900">{{ $item->nama_bank }} - {{ $item->no_rekening }}</div>
                                <div>a.n. {{ $item->atas_nama }}</div>
                            @else
                                <div>{{ $item->atas_nama ?? '-' }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <button type="button" data-modal-open="modal-edit-metode-{{ $item->id }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="{{ route('admin.metode-pembayaran.destroy', $item) }}"
                                data-title="Hapus Metode Pembayaran"
                                data-message="Yakin ingin menghapus metode {{ $item->nama_metode }}?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    <div id="modal-edit-metode-{{ $item->id }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Metode Pembayaran</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="{{ route('admin.metode-pembayaran.update', $item) }}" data-loading-form class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode</label>
                                        <input type="text" name="kode" value="{{ $item->kode }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe</label>
                                        <select name="tipe" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="tunai" @selected($item->tipe === 'tunai')>Tunai</option>
                                            <option value="transfer_bank" @selected($item->tipe === 'transfer_bank')>Transfer Bank</option>
                                            <option value="qris" @selected($item->tipe === 'qris')>QRIS</option>
                                            <option value="lainnya" @selected($item->tipe === 'lainnya')>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Metode</label>
                                    <input type="text" name="nama_metode" value="{{ $item->nama_metode }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank</label>
                                        <input type="text" name="nama_bank" value="{{ $item->nama_bank }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">No. Rekening</label>
                                        <input type="text" name="no_rekening" value="{{ $item->no_rekening }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Atas Nama</label>
                                        <input type="text" name="atas_nama" value="{{ $item->atas_nama }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                                    <textarea name="keterangan" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $item->keterangan }}</textarea>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active) class="rounded border-slate-300 text-[#1B4D3E]">
                                    <span>Aktif</span>
                                </label>
                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                                    <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $metodes->links() }}</div>
</div>

<div id="modal-tambah-metode" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Metode Pembayaran</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('admin.metode-pembayaran.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode</label>
                    <input type="text" name="kode" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe</label>
                    <select name="tipe" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="tunai">Tunai</option>
                        <option value="transfer_bank">Transfer Bank</option>
                        <option value="qris">QRIS</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Metode</label>
                <input type="text" name="nama_metode" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Bank</label>
                    <input type="text" name="nama_bank" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. Rekening</label>
                    <input type="text" name="no_rekening" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Atas Nama</label>
                    <input type="text" name="atas_nama" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-[#1B4D3E]">
                <span>Aktif</span>
            </label>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan</span></button>
            </div>
        </form>
    </div>
</div>
@endsection
