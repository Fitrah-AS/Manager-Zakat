<?php $__env->startSection('title', 'Standar Nisab Zakat Mal - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Manajemen Standar Nisab & Haul Zakat Mal'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-base font-bold text-slate-900">Daftar Standar Nisab per Jenis Harta</h2>
            <p class="text-xs text-slate-500">Digunakan pada kalkulator publik maupun pencatatan zakat mal resmi.</p>
        </div>
        <button type="button" data-modal-open="modal-tambah-nisab" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white cursor-pointer">
            + Tambah Standar Nisab
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Standar &amp; Jenis Harta</th>
                    <th class="px-5 py-3 font-semibold">Basis &amp; Nilai Nisab</th>
                    <th class="px-5 py-3 font-semibold">Kadar &amp; Haul</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $nisabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900"><?php echo e($item->nama_standar); ?></div>
                            <div class="text-xs text-slate-500 uppercase"><?php echo e(str_replace('_', ' ', $item->jenis_harta)); ?> <?php echo e($item->periode ? '• ' . $item->periode->tahun_hijriah : ''); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-700">
                            <div><?php echo e($item->basis_nisab); ?></div>
                            <?php if($item->jenis_harta === 'pertanian'): ?>
                                <div class="font-bold text-[#1B4D3E] mt-0.5"><?php echo e(number_format($item->nisab_pertanian_kg ?? 653, 0, ',', '.')); ?> kg gabah</div>
                            <?php else: ?>
                                <div class="font-bold text-[#1B4D3E] mt-0.5">Rp <?php echo e(number_format($item->nilai_nisab_uang, 0, ',', '.')); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs">
                            <div class="font-semibold text-slate-900"><?php echo e(number_format($item->kadar_zakat_persen, 2, ',', '.')); ?>%</div>
                            <div class="mt-0.5"><?php echo e($item->ketentuan_haul); ?></div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'); ?>">
                                <?php echo e($item->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                            <button type="button" data-modal-open="modal-edit-nisab-<?php echo e($item->id); ?>" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer">Edit</button>
                            <button
                                type="button"
                                data-confirm-delete
                                data-action="<?php echo e(route('admin.nisab.destroy', $item)); ?>"
                                data-title="Hapus Standar Nisab"
                                data-message="Yakin ingin menghapus standar nisab <?php echo e($item->nama_standar); ?>?"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                            >Hapus</button>
                        </td>
                    </tr>

                    <div id="modal-edit-nisab-<?php echo e($item->id); ?>" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Standar Nisab</h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.nisab.update', $item)); ?>" data-loading-form class="p-6 space-y-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Harta</label>
                                        <select name="jenis_harta" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="emas_perak" <?php if($item->jenis_harta === 'emas_perak'): echo 'selected'; endif; ?>>Emas &amp; Perak</option>
                                            <option value="penghasilan" <?php if($item->jenis_harta === 'penghasilan'): echo 'selected'; endif; ?>>Penghasilan / Profesi</option>
                                            <option value="perdagangan" <?php if($item->jenis_harta === 'perdagangan'): echo 'selected'; endif; ?>>Perdagangan</option>
                                            <option value="tabungan_investasi" <?php if($item->jenis_harta === 'tabungan_investasi'): echo 'selected'; endif; ?>>Tabungan &amp; Investasi</option>
                                            <option value="pertanian" <?php if($item->jenis_harta === 'pertanian'): echo 'selected'; endif; ?>>Pertanian</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Periode (Opsional)</label>
                                        <select name="periode_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="">Berlaku Umum</option>
                                            <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($p->id); ?>" <?php if($item->periode_id === $p->id): echo 'selected'; endif; ?>><?php echo e($p->nama_periode); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Standar</label>
                                        <input type="text" name="nama_standar" value="<?php echo e($item->nama_standar); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Basis Nisab</label>
                                        <input type="text" name="basis_nisab" value="<?php echo e($item->basis_nisab); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Emas/gr (Rp)</label>
                                        <input type="number" step="100" name="harga_emas_per_gram" value="<?php echo e($item->harga_emas_per_gram); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Gram Emas</label>
                                        <input type="number" step="0.01" name="gram_emas_nisab" value="<?php echo e($item->gram_emas_nisab); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nisab Tani (Kg)</label>
                                        <input type="number" step="0.01" name="nisab_pertanian_kg" value="<?php echo e($item->nisab_pertanian_kg); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kadar (%)</label>
                                        <input type="number" step="0.1" name="kadar_zakat_persen" value="<?php echo e($item->kadar_zakat_persen); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ketentuan Haul</label>
                                    <input type="text" name="ketentuan_haul" value="<?php echo e($item->ketentuan_haul); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                                    <textarea name="catatan" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"><?php echo e($item->catatan); ?></textarea>
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
    <div class="px-5 py-4 border-t border-slate-100"><?php echo e($nisabs->links()); ?></div>
</div>

<div id="modal-tambah-nisab" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Standar Nisab</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.nisab.store')); ?>" data-loading-form class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Harta</label>
                    <select name="jenis_harta" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="emas_perak">Emas &amp; Perak</option>
                        <option value="penghasilan">Penghasilan / Profesi</option>
                        <option value="perdagangan">Perdagangan</option>
                        <option value="tabungan_investasi">Tabungan &amp; Investasi</option>
                        <option value="pertanian">Pertanian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Periode (Opsional)</label>
                    <select name="periode_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Berlaku Umum</option>
                        <?php $__currentLoopData = $periodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->nama_periode); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Standar</label>
                    <input type="text" name="nama_standar" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Basis Nisab</label>
                    <input type="text" name="basis_nisab" placeholder="Contoh: Setara 85 gram emas" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Emas/gr (Rp)</label>
                    <input type="number" step="100" name="harga_emas_per_gram" value="1500000" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Gram Emas</label>
                    <input type="number" step="0.01" name="gram_emas_nisab" value="85" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nisab Tani (Kg)</label>
                    <input type="number" step="0.01" name="nisab_pertanian_kg" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kadar (%)</label>
                    <input type="number" step="0.1" name="kadar_zakat_persen" value="2.5" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Ketentuan Haul</label>
                <input type="text" name="ketentuan_haul" value="Telah dimiliki selama 1 tahun Hijriah penuh (haul)." required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/admin/nisab/index.blade.php ENDPATH**/ ?>