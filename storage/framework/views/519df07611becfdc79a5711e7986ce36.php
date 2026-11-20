<?php $__env->startSection('title', 'Buat Penyaluran Zakat - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Catat Penyaluran Zakat ke Mustahik'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('penyaluran.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">&larr; Kembali ke Daftar Penyaluran</a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Saldo Kas Uang Tersedia','value' => 'Rp ' . number_format($saldoKasUang, 0, ',', '.'),'sublabel' => 'Batas maksimal penyaluran uang','accent' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Saldo Kas Uang Tersedia','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Rp ' . number_format($saldoKasUang, 0, ',', '.')),'sublabel' => 'Batas maksimal penyaluran uang','accent' => 'emerald']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Stok Beras Tersedia','value' => number_format($stokBerasKg, 2, ',', '.') . ' kg','sublabel' => 'Batas maksimal penyaluran beras','accent' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Stok Beras Tersedia','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($stokBerasKg, 2, ',', '.') . ' kg'),'sublabel' => 'Batas maksimal penyaluran beras','accent' => 'gold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
</div>

<?php if(! $periodeAktif): ?>
    <div class="rounded-xl border border-amber-300 bg-amber-50 p-5 text-sm text-amber-900">
        Belum ada periode zakat aktif. Hubungi Admin untuk mengaktifkan periode terlebih dahulu.
    </div>
<?php elseif($mustahiks->isEmpty()): ?>
    <div class="rounded-xl border border-amber-300 bg-amber-50 p-5 text-sm text-amber-900">
        Belum ada mustahik berstatus <strong>Layak</strong>. Lakukan survei kelayakan mustahik terlebih dahulu sebelum membuat penyaluran.
    </div>
<?php else: ?>
<form id="form-penyaluran-zakat" method="POST" action="<?php echo e(route('penyaluran.store')); ?>" data-loading-form class="space-y-6">
    <?php echo csrf_field(); ?>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Penyaluran</label>
                <input type="datetime-local" name="tanggal_penyaluran" value="<?php echo e(old('tanggal_penyaluran', now()->format('Y-m-d\TH:i'))); ?>" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan Umum</label>
                <input type="text" name="keterangan" value="<?php echo e(old('keterangan')); ?>" placeholder="Contoh: Penyaluran Zakat Fitrah Tahap 1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-slate-900">Daftar Mustahik Penerima (Berstatus Layak)</h2>
                <p class="text-xs text-slate-500">Pilih mustahik, asnaf, jenis zakat, dan tentukan bentuk salur: Uang (Rp), Beras (Kg), atau Uang &amp; Beras sekaligus.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-slate-50 p-1 text-[11px] font-semibold">
                    <span class="px-2 text-slate-500">Set Semua:</span>
                    <button type="button" data-set-all-bentuk="uang" class="rounded-md bg-white hover:bg-emerald-50 border border-slate-200 px-2.5 py-1 text-slate-700 cursor-pointer">Uang (Rp)</button>
                    <button type="button" data-set-all-bentuk="beras" class="rounded-md bg-amber-50 hover:bg-amber-100 border border-amber-200 px-2.5 py-1 text-amber-800 cursor-pointer">Beras (Kg)</button>
                    <button type="button" data-set-all-bentuk="keduanya" class="rounded-md bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1 text-[#1B4D3E] cursor-pointer">Uang &amp; Beras</button>
                </div>
                <button type="button" id="btn-tambah-penerima" class="rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-3.5 py-2 text-xs font-bold text-[#1B4D3E] cursor-pointer">
                    + Tambah Penerima
                </button>
            </div>
        </div>

        <div id="penyaluran-items-container" class="space-y-3">
            <div data-salur-row class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="lg:col-span-3">
                    <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Mustahik *</label>
                    <select data-salur-mustahik name="items[0][mustahik_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                        <option value="">-- Pilih Mustahik Layak --</option>
                        <?php $__currentLoopData = $mustahiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m->id); ?>" data-asnaf-id="<?php echo e($m->asnaf_id); ?>"><?php echo e($m->nama_lengkap); ?> (<?php echo e($m->asnaf->nama_asnaf ?? '-'); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Kategori Asnaf *</label>
                    <select data-salur-asnaf name="items[0][asnaf_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                        <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($a->id); ?>"><?php echo e($a->nama_asnaf); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Sumber Jenis Zakat *</label>
                    <select name="items[0][jenis_zakat_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                        <?php $__currentLoopData = $jenisZakats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($jz->id); ?>"><?php echo e($jz->nama_jenis); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Bentuk Salur *</label>
                    <select data-salur-bentuk name="items[0][bentuk_salur]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                        <option value="uang">Uang (Rp)</option>
                        <option value="beras">Beras (Kg)</option>
                        <option value="keduanya">Uang &amp; Beras</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex flex-col gap-2">
                    <div data-box-uang>
                        <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Nominal (Rp)</label>
                        <input data-salur-uang type="number" step="100" min="0" name="items[0][nominal_uang]" value="0" class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                    </div>
                    <div data-box-beras class="hidden">
                        <label class="block text-[11px] leading-4 font-semibold text-amber-800 mb-1.5">Jumlah Beras (Kg)</label>
                        <input data-salur-beras type="number" step="0.1" min="0" name="items[0][jumlah_beras_kg]" value="0" class="w-full h-[38px] rounded-lg border border-amber-300 bg-white px-3 text-xs">
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <span class="hidden lg:block text-[11px] leading-4 mb-1.5 select-none">&nbsp;</span>
                    <button type="button" data-remove-salur-row class="w-full h-[38px] rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-2.5 text-xs font-semibold text-red-700 cursor-pointer">Hapus</button>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div>Total Uang: <span id="summary-salur-uang" class="font-bold text-[#1B4D3E]">Rp 0</span></div>
                <div>Total Beras: <span id="summary-salur-beras" class="font-bold text-amber-700">0,00 kg</span></div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <label class="inline-flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="langsung_selesai" value="1" class="rounded border-slate-300 text-[#1B4D3E]">
                    <span>Langsung Selesaikan Penyaluran (Potong Kas/Stok)</span>
                </label>

                <button type="submit" data-loading-text="Menyimpan..." class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-5 py-2.5 text-xs font-bold text-white shadow cursor-pointer">
                    <span data-submit-label>Simpan Penyaluran</span>
                </button>
            </div>
        </div>
    </div>
</form>

<template id="penyaluran-row-template">
    <div data-salur-row class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
        <div class="lg:col-span-3">
            <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Mustahik *</label>
            <select data-salur-mustahik name="items[__INDEX__][mustahik_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                <option value="">-- Pilih Mustahik Layak --</option>
                <?php $__currentLoopData = $mustahiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m->id); ?>" data-asnaf-id="<?php echo e($m->asnaf_id); ?>"><?php echo e($m->nama_lengkap); ?> (<?php echo e($m->asnaf->nama_asnaf ?? '-'); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Kategori Asnaf *</label>
            <select data-salur-asnaf name="items[__INDEX__][asnaf_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                <?php $__currentLoopData = $asnafs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($a->id); ?>"><?php echo e($a->nama_asnaf); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Sumber Jenis Zakat *</label>
            <select name="items[__INDEX__][jenis_zakat_id]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                <?php $__currentLoopData = $jenisZakats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($jz->id); ?>"><?php echo e($jz->nama_jenis); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="lg:col-span-2">
            <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Bentuk Salur *</label>
            <select data-salur-bentuk name="items[__INDEX__][bentuk_salur]" required class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
                <option value="uang">Uang (Rp)</option>
                <option value="beras">Beras (Kg)</option>
                <option value="keduanya">Uang &amp; Beras</option>
            </select>
        </div>
        <div class="lg:col-span-2 flex flex-col gap-2">
            <div data-box-uang>
                <label class="block text-[11px] leading-4 font-semibold text-slate-600 mb-1.5">Nominal (Rp)</label>
                <input data-salur-uang type="number" step="100" min="0" name="items[__INDEX__][nominal_uang]" value="0" class="w-full h-[38px] rounded-lg border border-slate-300 bg-white px-3 text-xs">
            </div>
            <div data-box-beras class="hidden">
                <label class="block text-[11px] leading-4 font-semibold text-amber-800 mb-1.5">Jumlah Beras (Kg)</label>
                <input data-salur-beras type="number" step="0.1" min="0" name="items[__INDEX__][jumlah_beras_kg]" value="0" class="w-full h-[38px] rounded-lg border border-amber-300 bg-white px-3 text-xs">
            </div>
        </div>
        <div class="lg:col-span-1">
            <span class="hidden lg:block text-[11px] leading-4 mb-1.5 select-none">&nbsp;</span>
            <button type="button" data-remove-salur-row class="w-full h-[38px] rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-2.5 text-xs font-semibold text-red-700 cursor-pointer">Hapus</button>
        </div>
    </div>
</template>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.initPenyaluranForm === 'function') {
        window.initPenyaluranForm();
        return;
    }

    var form = document.getElementById('form-penyaluran-zakat');
    if (!form || form.dataset.penyaluranBound === '1') return;
    form.dataset.penyaluranBound = '1';

    var container = document.getElementById('penyaluran-items-container');
    var template = document.getElementById('penyaluran-row-template');
    var btnTambah = document.getElementById('btn-tambah-penerima');
    var totalUangEl = document.getElementById('summary-salur-uang');
    var totalBerasEl = document.getElementById('summary-salur-beras');
    var rowIndex = container ? container.querySelectorAll('[data-salur-row]').length : 0;

    function updateTotals() {
        var sumUang = 0;
        var sumBeras = 0;
        container.querySelectorAll('[data-salur-row]').forEach(function (row) {
            var bentuk = row.querySelector('[data-salur-bentuk]') ? row.querySelector('[data-salur-bentuk]').value : 'uang';
            if (bentuk === 'uang' || bentuk === 'keduanya') {
                sumUang += parseFloat((row.querySelector('[data-salur-uang]') || {}).value || '0');
            }
            if (bentuk === 'beras' || bentuk === 'keduanya') {
                sumBeras += parseFloat((row.querySelector('[data-salur-beras]') || {}).value || '0');
            }
        });
        if (totalUangEl) totalUangEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(sumUang);
        if (totalBerasEl) totalBerasEl.textContent = sumBeras.toFixed(2).replace('.', ',') + ' kg';
    }

    function bindRow(row) {
        var mustahikSelect = row.querySelector('[data-salur-mustahik]');
        var asnafSelect = row.querySelector('[data-salur-asnaf]');
        var bentukSelect = row.querySelector('[data-salur-bentuk]');
        var uangBox = row.querySelector('[data-box-uang]');
        var berasBox = row.querySelector('[data-box-beras]');
        var uangInput = row.querySelector('[data-salur-uang]');
        var berasInput = row.querySelector('[data-salur-beras]');
        var removeBtn = row.querySelector('[data-remove-salur-row]');

        function syncBentuk() {
            var val = bentukSelect ? bentukSelect.value : 'uang';
            if (uangBox) uangBox.classList.toggle('hidden', val !== 'uang' && val !== 'keduanya');
            if (berasBox) berasBox.classList.toggle('hidden', val !== 'beras' && val !== 'keduanya');
            updateTotals();
        }

        if (mustahikSelect && asnafSelect) {
            mustahikSelect.addEventListener('change', function () {
                var opt = mustahikSelect.options[mustahikSelect.selectedIndex];
                if (opt && opt.dataset.asnafId) asnafSelect.value = opt.dataset.asnafId;
            });
        }
        if (bentukSelect) bentukSelect.addEventListener('change', syncBentuk);
        if (uangInput) uangInput.addEventListener('input', updateTotals);
        if (berasInput) berasInput.addEventListener('input', updateTotals);
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (container.querySelectorAll('[data-salur-row]').length <= 1) return;
                row.remove();
                updateTotals();
            });
        }
        syncBentuk();
    }

    if (container) container.querySelectorAll('[data-salur-row]').forEach(bindRow);

    document.querySelectorAll('[data-set-all-bentuk]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = btn.getAttribute('data-set-all-bentuk');
            container.querySelectorAll('[data-salur-bentuk]').forEach(function (select) {
                select.value = target;
                select.dispatchEvent(new Event('change'));
            });
        });
    });

    if (btnTambah && template && container) {
        btnTambah.addEventListener('click', function () {
            var html = template.innerHTML.replace(/__INDEX__/g, String(rowIndex++));
            var wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            var newRow = wrapper.firstElementChild;
            if (newRow) {
                container.appendChild(newRow);
                bindRow(newRow);
                updateTotals();
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/penyaluran/create.blade.php ENDPATH**/ ?>