@extends('layouts.app')

@section('title', 'Data Mustahik - Sistem Informasi Zakat')
@section('page_title', 'Manajemen Data Mustahik & Survei')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="{{ route('mustahik.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama, NIK, HP, alamat..."
                class="w-full sm:w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none"
            >
            <select name="asnaf_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Asnaf</option>
                @foreach ($asnafs as $a)
                    <option value="{{ $a->id }}" @selected((string) $asnafId === (string) $a->id)>{{ $a->nama_asnaf }}</option>
                @endforeach
            </select>
            <select name="status_kelayakan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Kelayakan</option>
                <option value="menunggu_survei" @selected($statusKelayakan === 'menunggu_survei')>Menunggu Survei</option>
                <option value="layak" @selected($statusKelayakan === 'layak')>Layak</option>
                <option value="tidak_layak" @selected($statusKelayakan === 'tidak_layak')>Tidak Layak</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <button
            type="button"
            data-modal-open="modal-tambah-mustahik"
            class="inline-flex items-center justify-center rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs cursor-pointer"
        >
            + Tambah Mustahik
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Nama &amp; NIK</th>
                    <th class="px-5 py-3 font-semibold">Asnaf</th>
                    <th class="px-5 py-3 font-semibold">Kontak &amp; Lokasi</th>
                    <th class="px-5 py-3 font-semibold">Kelayakan</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($mustahiks as $item)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('mustahik.show', $item) }}" class="font-semibold text-[#1B4D3E] hover:underline">
                                {{ $item->nama_lengkap }}
                            </a>
                            <div class="text-xs text-slate-500">NIK: {{ $item->nik ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-700">{{ $item->asnaf->nama_asnaf ?? '-' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div class="font-medium text-slate-800">{{ $item->no_hp }}</div>
                            <div>{{ Str::limit($item->alamat, 55) }}</div>
                            @if ($item->latitude && $item->longitude)
                                <div class="text-[11px] text-emerald-700 mt-0.5">GPS: {{ $item->latitude }}, {{ $item->longitude }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <x-badge-status :status="$item->status_kelayakan" />
                            <div class="text-[11px] text-slate-400 mt-1">{{ $item->survei_count }} survei</div>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="{{ route('mustahik.show', $item) }}" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Detail &amp; Survei</a>
                            <button type="button" data-modal-open="modal-edit-mustahik-{{ $item->id }}" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="{{ route('mustahik.destroy', $item) }}"
                                data-title="Hapus Data Mustahik"
                                data-message="Yakin ingin menghapus data mustahik {{ $item->nama_lengkap }}?"
                                class="rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    {{-- Modal Edit Mustahik --}}
                    <div id="modal-edit-mustahik-{{ $item->id }}" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Mustahik: {{ $item->nama_lengkap }}</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="{{ route('mustahik.update', $item) }}" data-loading-form class="p-6 space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="{{ $item->nama_lengkap }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                                        <input type="text" name="nik" value="{{ $item->nik }}" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP</label>
                                        <input type="text" name="no_hp" value="{{ $item->no_hp }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Asnaf</label>
                                        <select name="asnaf_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            @foreach ($asnafs as $a)
                                                <option value="{{ $a->id }}" @selected($item->asnaf_id === $a->id)>{{ $a->nama_asnaf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kelayakan</label>
                                        <select name="status_kelayakan" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="menunggu_survei" @selected($item->status_kelayakan->value === 'menunggu_survei')>Menunggu Survei</option>
                                            <option value="layak" @selected($item->status_kelayakan->value === 'layak')>Layak</option>
                                            <option value="tidak_layak" @selected($item->status_kelayakan->value === 'tidak_layak')>Tidak Layak</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" rows="2" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $item->alamat }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Latitude (Opsional)</label>
                                        <input type="number" step="0.0000001" name="latitude" value="{{ $item->latitude }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Longitude (Opsional)</label>
                                        <input type="number" step="0.0000001" name="longitude" value="{{ $item->longitude }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
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
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada data mustahik.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $mustahiks->links() }}</div>
</div>

{{-- Modal Tambah Mustahik --}}
<div id="modal-tambah-mustahik" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Mustahik Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="{{ route('mustahik.store') }}" data-loading-form class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                    <input type="text" name="nik" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Asnaf</label>
                    <select name="asnaf_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($asnafs as $a)
                            <option value="{{ $a->id }}">{{ $a->nama_asnaf }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Awal</label>
                    <select name="status_kelayakan" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="menunggu_survei">Menunggu Survei</option>
                        <option value="layak">Layak</option>
                        <option value="tidak_layak">Tidak Layak</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Latitude (Opsional)</label>
                    <input id="tambah-lat" type="number" step="0.0000001" name="latitude" placeholder="-6.2000000" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Longitude (Opsional)</label>
                    <input id="tambah-lng" type="number" step="0.0000001" name="longitude" placeholder="106.8166667" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <button type="button" id="btn-ambil-gps" class="text-xs font-semibold text-[#1B4D3E] hover:underline cursor-pointer">
                    Gunakan Koordinat GPS Perangkat Saat Ini
                </button>
                <p id="gps-status" class="text-[11px] text-slate-500 mt-0.5"></p>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan Mustahik</span></button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gpsBtn = document.getElementById('btn-ambil-gps');
    const gpsStatus = document.getElementById('gps-status');
    const latInput = document.getElementById('tambah-lat');
    const lngInput = document.getElementById('tambah-lng');

    if (gpsBtn && latInput && lngInput) {
        gpsBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                if (gpsStatus) gpsStatus.textContent = 'Browser tidak mendukung geolokasi.';
                return;
            }
            if (gpsStatus) gpsStatus.textContent = 'Mengambil koordinat lokasi...';
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    latInput.value = pos.coords.latitude.toFixed(7);
                    lngInput.value = pos.coords.longitude.toFixed(7);
                    if (gpsStatus) gpsStatus.textContent = 'Koordinat berhasil diambil.';
                },
                () => {
                    if (gpsStatus) gpsStatus.textContent = 'Izin lokasi ditolak atau koordinat tidak tersedia.';
                }
            );
        });
    }
});
</script>
@endpush
