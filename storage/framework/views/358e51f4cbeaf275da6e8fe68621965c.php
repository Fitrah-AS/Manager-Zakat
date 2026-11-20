<?php $__env->startSection('title', 'Data Mustahik - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Manajemen Data Mustahik & Survei'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <form method="GET" action="<?php echo e(route('mustahik.index')); ?>" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="<?php echo e($search); ?>"
                placeholder="Cari nama, NIK, HP, alamat..."
                class="w-full sm:w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none"
            >
            <select name="asnaf_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Asnaf</option>
                <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($a->id); ?>" <?php if((string) $asnafId === (string) $a->id): echo 'selected'; endif; ?>><?php echo e($a->nama_asnaf); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status_kelayakan" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Kelayakan</option>
                <option value="menunggu_survei" <?php if($statusKelayakan === 'menunggu_survei'): echo 'selected'; endif; ?>>Menunggu Survei</option>
                <option value="layak" <?php if($statusKelayakan === 'layak'): echo 'selected'; endif; ?>>Layak</option>
                <option value="tidak_layak" <?php if($statusKelayakan === 'tidak_layak'): echo 'selected'; endif; ?>>Tidak Layak</option>
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
                <?php $__empty_1 = true; $__currentLoopData = $mustahiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <a href="<?php echo e(route('mustahik.show', $item)); ?>" class="font-semibold text-[#1B4D3E] hover:underline">
                                <?php echo e($item->nama_lengkap); ?>

                            </a>
                            <div class="text-xs text-slate-500">NIK: <?php echo e($item->nik ?? '-'); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-700"><?php echo e($item->asnaf->nama_asnaf ?? '-'); ?></td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div class="font-medium text-slate-800"><?php echo e($item->no_hp); ?></div>
                            <div><?php echo e(Str::limit($item->alamat, 55)); ?></div>
                            <?php if($item->latitude && $item->longitude): ?>
                                <div class="text-[11px] text-emerald-700 mt-0.5">GPS: <?php echo e($item->latitude); ?>, <?php echo e($item->longitude); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5">
                            <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $item->status_kelayakan]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->status_kelayakan)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal435aefee4aa6dd7f20df034696ae03b9)): ?>
<?php $attributes = $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9; ?>
<?php unset($__attributesOriginal435aefee4aa6dd7f20df034696ae03b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal435aefee4aa6dd7f20df034696ae03b9)): ?>
<?php $component = $__componentOriginal435aefee4aa6dd7f20df034696ae03b9; ?>
<?php unset($__componentOriginal435aefee4aa6dd7f20df034696ae03b9); ?>
<?php endif; ?>
                            <div class="text-[11px] text-slate-400 mt-1"><?php echo e($item->survei_count); ?> survei</div>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <a href="<?php echo e(route('mustahik.show', $item)); ?>" class="inline-flex rounded-lg border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-[#1B4D3E]">Detail &amp; Survei</a>
                            <button type="button" data-modal-open="modal-edit-mustahik-<?php echo e($item->id); ?>" class="rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="<?php echo e(route('mustahik.destroy', $item)); ?>"
                                data-title="Hapus Data Mustahik"
                                data-message="Yakin ingin menghapus data mustahik <?php echo e($item->nama_lengkap); ?>?"
                                class="rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    
                    <div id="modal-edit-mustahik-<?php echo e($item->id); ?>" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Mustahik: <?php echo e($item->nama_lengkap); ?></h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="<?php echo e(route('mustahik.update', $item)); ?>" data-loading-form class="p-6 space-y-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="<?php echo e($item->nama_lengkap); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit, Opsional)</label>
                                        <input type="text" name="nik" value="<?php echo e($item->nik); ?>" maxlength="16" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP</label>
                                        <input type="text" name="no_hp" value="<?php echo e($item->no_hp); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Asnaf</label>
                                        <select name="asnaf_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($a->id); ?>" <?php if($item->asnaf_id === $a->id): echo 'selected'; endif; ?>><?php echo e($a->nama_asnaf); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kelayakan</label>
                                        <select name="status_kelayakan" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="menunggu_survei" <?php if($item->status_kelayakan->value === 'menunggu_survei'): echo 'selected'; endif; ?>>Menunggu Survei</option>
                                            <option value="layak" <?php if($item->status_kelayakan->value === 'layak'): echo 'selected'; endif; ?>>Layak</option>
                                            <option value="tidak_layak" <?php if($item->status_kelayakan->value === 'tidak_layak'): echo 'selected'; endif; ?>>Tidak Layak</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                                    <textarea name="alamat" rows="2" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?php echo e($item->alamat); ?></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Latitude (Opsional)</label>
                                        <input type="number" step="0.0000001" name="latitude" value="<?php echo e($item->latitude); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Longitude (Opsional)</label>
                                        <input type="number" step="0.0000001" name="longitude" value="<?php echo e($item->longitude); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                                    <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan Perubahan</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada data mustahik.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($mustahiks->links()); ?></div>
</div>


<div id="modal-tambah-mustahik" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Mustahik Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="<?php echo e(route('mustahik.store')); ?>" data-loading-form class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
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
                        <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($a->id); ?>"><?php echo e($a->nama_asnaf); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/mustahik/index.blade.php ENDPATH**/ ?>