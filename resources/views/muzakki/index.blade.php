@extends('layouts.app')

@section('title', 'Data Muzakki - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Data Muzakki')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="{{ route('muzakki.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama, NIK, nomor HP, atau email..."
                class="w-full sm:w-72 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none focus:ring-2 focus:ring-[#1B4D3E]/20"
            >
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Cari
            </button>
            @if ($search !== '')
                <a href="{{ route('muzakki.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Reset</a>
            @endif
        </form>

        <button
            type="button"
            data-modal-open="modal-tambah-muzakki"
            class="inline-flex items-center justify-center rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs cursor-pointer"
        >
            + Tambah Muzakki
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Nama &amp; NIK</th>
                    <th class="px-5 py-3 font-semibold">Kontak</th>
                    <th class="px-5 py-3 font-semibold">Alamat</th>
                    <th class="px-5 py-3 font-semibold">Transaksi</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($muzakkis as $item)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('muzakki.show', $item) }}" class="font-semibold text-[#1B4D3E] hover:underline">
                                {{ $item->nama_lengkap }}
                            </a>
                            <div class="text-xs text-slate-500">NIK: {{ $item->nik ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div class="font-medium">{{ $item->no_hp }}</div>
                            <div class="text-slate-500">{{ $item->email ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs">{{ Str::limit($item->alamat, 70) }}</td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-700">{{ $item->pembayaran_count }} kali</td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="{{ route('muzakki.show', $item) }}" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Detail</a>
                            <button type="button" data-modal-open="modal-edit-muzakki-{{ $item->id }}" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="{{ route('muzakki.destroy', $item) }}"
                                data-title="Hapus Data Muzakki"
                                data-message="Yakin ingin menghapus data muzakki {{ $item->nama_lengkap }}?"
                                class="rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    {{-- Modal Edit Muzakki --}}
                    <div id="modal-edit-muzakki-{{ $item->id }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Muzakki: {{ $item->nama_lengkap }}</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="{{ route('muzakki.update', $item) }}" data-loading-form class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" value="{{ $item->nama_lengkap }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                                        <input type="text" name="nik" value="{{ $item->nik }}" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP</label>
                                        <input type="text" name="no_hp" value="{{ $item->no_hp }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                                    <input type="email" name="email" value="{{ $item->email }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" rows="3" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $item->alamat }}</textarea>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                                    <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan Perubahan</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada data muzakki.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $muzakkis->links() }}</div>
</div>

{{-- Modal Tambah Muzakki --}}
<div id="modal-tambah-muzakki" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Muzakki Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('muzakki.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                    <input type="text" name="nik" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                <input type="email" name="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan Muzakki</span></button>
            </div>
        </form>
    </div>
</div>
@endsection
