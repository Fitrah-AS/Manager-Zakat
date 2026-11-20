<?php $__env->startSection('title', 'Data Asnaf - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Manajemen Data Asnaf (8 Golongan)'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Golongan Asnaf</h2>
            <p class="text-xs text-slate-500">Golongan penerima zakat sesuai ketentuan syariat.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-asnaf" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer">
            + Tambah Asnaf
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Urutan</th>
                    <th class="px-5 py-3 font-semibold">Kode &amp; Nama Asnaf</th>
                    <th class="px-5 py-3 font-semibold">Deskripsi</th>
                    <th class="px-5 py-3 font-semibold">Mustahik</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5 font-semibold text-slate-600"><?php echo e($item->urutan); ?></td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900"><?php echo e($item->nama_asnaf); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($item->kode); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-md"><?php echo e($item->deskripsi); ?></td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-slate-700"><?php echo e($item->mustahik_count); ?> orang</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'); ?>">
                                <?php echo e($item->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <button type="button" data-modal-open="modal-edit-asnaf-<?php echo e($item->id); ?>" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="<?php echo e(route('admin.asnaf.destroy', $item)); ?>"
                                data-title="Hapus Data Asnaf"
                                data-message="Yakin ingin menghapus asnaf <?php echo e($item->nama_asnaf); ?>?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    <div id="modal-edit-asnaf-<?php echo e($item->id); ?>" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Asnaf: <?php echo e($item->nama_asnaf); ?></h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.asnaf.update', $item)); ?>" data-loading-form class="p-6 space-y-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode</label>
                                        <input type="text" name="kode" value="<?php echo e($item->kode); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Asnaf</label>
                                        <input type="text" name="nama_asnaf" value="<?php echo e($item->nama_asnaf); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                                        <input type="number" name="urutan" value="<?php echo e($item->urutan); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi</label>
                                    <textarea name="deskripsi" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?php echo e($item->deskripsi); ?></textarea>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" <?php if($item->is_active): echo 'checked'; endif; ?> class="rounded border-slate-300 text-[#1B4D3E]">
                                    <span>Aktif</span>
                                </label>
                                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                                    <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                                    <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] px-4 py-2 text-xs font-semibold text-white cursor-pointer"><span data-submit-label>Simpan</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($asnafs->links()); ?></div>
</div>

<div id="modal-tambah-asnaf" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Asnaf Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.asnaf.store')); ?>" data-loading-form class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kode</label>
                    <input type="text" name="kode" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Asnaf</label>
                    <input type="text" name="nama_asnaf" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Urutan</label>
                    <input type="number" name="urutan" value="9" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/admin/asnaf/index.blade.php ENDPATH**/ ?>