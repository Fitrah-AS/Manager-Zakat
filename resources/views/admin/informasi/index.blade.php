@extends('layouts.app')

@section('title', 'Informasi Zakat & Lembaga - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Informasi Zakat & Lembaga')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Konten Halaman Publik</h2>
            <p class="text-xs text-slate-500">Artikel edukasi zakat fitrah, zakat mal, profil lembaga, dan pengumuman untuk pengunjung.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-info" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer">
            + Tambah Informasi
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Judul</th>
                    <th class="px-5 py-3 font-semibold">Kategori</th>
                    <th class="px-5 py-3 font-semibold">Publikasi</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($informasis as $item)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900">{{ $item->judul }}</div>
                            <div class="text-xs text-slate-500 line-clamp-1">{{ Str::limit(strip_tags($item->konten), 90) }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold uppercase text-[#1B4D3E]">{{ $item->kategori }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $item->is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $item->is_published ? 'Dipublikasikan' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <button type="button" data-modal-open="modal-edit-info-{{ $item->id }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="{{ route('admin.informasi.destroy', $item) }}"
                                data-title="Hapus Informasi"
                                data-message="Yakin ingin menghapus informasi '{{ $item->judul }}'?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    <div id="modal-edit-info-{{ $item->id }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Informasi Publik</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="{{ route('admin.informasi.update', $item) }}" data-loading-form class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Judul</label>
                                        <input type="text" name="judul" value="{{ $item->judul }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                                        <select name="kategori" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="fitrah" @selected($item->kategori === 'fitrah')>Fitrah</option>
                                            <option value="mal" @selected($item->kategori === 'mal')>Mal</option>
                                            <option value="lembaga" @selected($item->kategori === 'lembaga')>Lembaga</option>
                                            <option value="pengumuman" @selected($item->kategori === 'pengumuman')>Pengumuman</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Konten</label>
                                    <textarea name="konten" rows="6" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $item->konten }}</textarea>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="is_published" value="1" @checked($item->is_published) class="rounded border-slate-300 text-[#1B4D3E]">
                                    <span>Tampilkan di Halaman Publik</span>
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
    <div class="px-5 py-4 border-t border-slate-100">{{ $informasis->links() }}</div>
</div>

<div id="modal-tambah-info" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Informasi Publik</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('admin.informasi.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul</label>
                    <input type="text" name="judul" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="fitrah">Fitrah</option>
                        <option value="mal">Mal</option>
                        <option value="lembaga">Lembaga</option>
                        <option value="pengumuman">Pengumuman</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Konten</label>
                <textarea name="konten" rows="6" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_published" value="1" checked class="rounded border-slate-300 text-[#1B4D3E]">
                <span>Tampilkan di Halaman Publik</span>
            </label>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan</span></button>
            </div>
        </form>
    </div>
</div>
@endsection
