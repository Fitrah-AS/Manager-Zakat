<?php $__env->startSection('title', 'Manajemen Akun Pengguna - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Manajemen Akun & Hak Akses'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="flex flex-wrap items-center gap-2.5 flex-1">
            <input
                type="text"
                name="q"
                value="<?php echo e($search); ?>"
                placeholder="Cari nama, username, atau email..."
                class="w-full sm:w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none focus:ring-2 focus:ring-[#1B4D3E]/20"
            >
            <select name="role" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none">
                <option value="">Semua Role</option>
                <option value="admin" <?php if($role === 'admin'): echo 'selected'; endif; ?>>Admin / Pimpinan</option>
                <option value="petugas" <?php if($role === 'petugas'): echo 'selected'; endif; ?>>Petugas / Amil</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-800 hover:bg-slate-900 px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Filter
            </button>
        </form>

        <button
            type="button"
            data-modal-open="modal-tambah-user"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2.5 text-xs font-semibold text-white shadow-xs cursor-pointer"
        >
            <span>+ Tambah Pengguna</span>
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Nama &amp; Username</th>
                    <th class="px-5 py-3 font-semibold">Email</th>
                    <th class="px-5 py-3 font-semibold">Role</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-900"><?php echo e($item->name); ?></div>
                            <div class="text-xs text-slate-500">&#64;<?php echo e($item->username); ?></div>
                        </td>
                        <td class="px-5 py-3.5 text-slate-600"><?php echo e($item->email ?? '-'); ?></td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($item->isAdmin() ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-[#0F5132]'); ?>">
                                <?php echo e($item->role->label()); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?php echo e($item->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'); ?>">
                                <?php echo e($item->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right space-x-2 whitespace-nowrap">
                            <button
                                type="button"
                                data-modal-open="modal-edit-user-<?php echo e($item->id); ?>"
                                class="inline-flex items-center rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 cursor-pointer"
                            >
                                Edit
                            </button>
                            <?php if($item->id !== auth()->id()): ?>
                                <button
                                    type="button"
                                    data-confirm-delete
                                    data-action="<?php echo e(route('admin.users.destroy', $item)); ?>"
                                    data-title="Hapus Akun Pengguna"
                                    data-message="Apakah Anda yakin ingin menghapus akun <?php echo e($item->name); ?>?"
                                    class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 cursor-pointer"
                                >
                                    Hapus
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    
                    <div id="modal-edit-user-<?php echo e($item->id); ?>" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
                                <h3 class="text-base font-bold">Edit Akun: <?php echo e($item->name); ?></h3>
                                <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
                            </div>
                            <form method="POST" action="<?php echo e(route('admin.users.update', $item)); ?>" data-loading-form class="p-6 space-y-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" value="<?php echo e($item->name); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                                        <input type="text" name="username" value="<?php echo e($item->username); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                                        <input type="email" name="email" value="<?php echo e($item->email); ?>" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hak Akses (Role)</label>
                                        <select name="role" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="admin" <?php if($item->role->value === 'admin'): echo 'selected'; endif; ?>>Admin / Pimpinan</option>
                                            <option value="petugas" <?php if($item->role->value === 'petugas'): echo 'selected'; endif; ?>>Petugas / Amil</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Opsional)</label>
                                        <input type="password" name="password" placeholder="Kosongkan jika tetap" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                </div>
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" name="is_active" value="1" <?php if($item->is_active): echo 'checked'; endif; ?> class="rounded border-slate-300 text-[#1B4D3E]">
                                    <span>Akun Aktif</span>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Tidak ada data pengguna ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100">
        <?php echo e($users->links()); ?>

    </div>
</div>


<div id="modal-tambah-user" data-modal-root class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 bg-[#1B4D3E] text-white flex items-center justify-between">
            <h3 class="text-base font-bold">Tambah Akun Pengguna Baru</h3>
            <button type="button" data-modal-close class="text-emerald-100 hover:text-white text-sm cursor-pointer">Tutup</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" data-loading-form class="p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Opsional)</label>
                    <input type="email" name="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Hak Akses (Role)</label>
                    <select name="role" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="petugas">Petugas / Amil</option>
                        <option value="admin">Admin / Pimpinan</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" required minlength="8" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-[#1B4D3E]">
                <span>Akun Aktif</span>
            </label>
            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" data-modal-close class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 cursor-pointer">Batal</button>
                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                    <span data-submit-label>Simpan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/admin/users/index.blade.php ENDPATH**/ ?>