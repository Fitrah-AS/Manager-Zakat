<?php $__env->startSection('title', 'Dashboard - Sistem Informasi Zakat'); ?>
<?php $__env->startSection('page_title', 'Dashboard Ringkasan Zakat'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $formatRupiah = fn (float|int $angka): string => 'Rp ' . number_format($angka, 0, ',', '.');
    $formatKg = fn (float|int $kg): string => number_format($kg, 2, ',', '.') . ' kg';
?>


<div class="mb-6 rounded-2xl bg-gradient-to-r from-[#1B4D3E] to-[#0F5132] p-6 text-white shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-[#C9A227]/40 px-3 py-1 text-xs font-medium text-[#F2D57E] mb-2">
            <span><?php echo e($periodeAktif ? $periodeAktif->nama_periode : 'Periode Belum Diaktifkan'); ?></span>
        </div>
        <h2 class="text-xl sm:text-2xl font-bold tracking-tight">Assalamu'alaikum, <?php echo e(auth()->user()->name); ?></h2>
        <p class="text-sm text-emerald-100/90 mt-1">
            Seluruh metrik di bawah dihitung secara langsung dari transaksi resmi terverifikasi di database.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
        <?php if(\Illuminate\Support\Facades\Route::has('pembayaran.create')): ?>
            <a href="<?php echo e(route('pembayaran.create')); ?>" class="inline-flex items-center gap-2 rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-950 shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Pembayaran Zakat</span>
            </a>
        <?php endif; ?>

        <?php if(\Illuminate\Support\Facades\Route::has('verifikasi.index')): ?>
            <a href="<?php echo e(route('verifikasi.index')); ?>" class="inline-flex items-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white transition">
                <span>Verifikasi (<?php echo e($menungguVerifikasiCount); ?>)</span>
            </a>
        <?php endif; ?>
    </div>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Jumlah Muzakki','value' => number_format($jumlahMuzakki, 0, ',', '.'),'sublabel' => 'Total pembayar zakat terdaftar','accent' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jumlah Muzakki','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($jumlahMuzakki, 0, ',', '.')),'sublabel' => 'Total pembayar zakat terdaftar','accent' => 'emerald']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
         <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Jumlah Mustahik','value' => number_format($jumlahMustahik, 0, ',', '.'),'sublabel' => number_format($jumlahMustahikLayak, 0, ',', '.') . ' berstatus layak terima','accent' => 'teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Jumlah Mustahik','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($jumlahMustahik, 0, ',', '.')),'sublabel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($jumlahMustahikLayak, 0, ',', '.') . ' berstatus layak terima'),'accent' => 'teal']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
         <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Penerimaan Zakat (Uang)','value' => $formatRupiah($totalPenerimaanUang),'sublabel' => 'Hanya transaksi terverifikasi','accent' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Penerimaan Zakat (Uang)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formatRupiah($totalPenerimaanUang)),'sublabel' => 'Hanya transaksi terverifikasi','accent' => 'emerald']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
            </svg>
         <?php $__env->endSlot(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Penerimaan Zakat (Beras)','value' => $formatKg($totalPenerimaanBerasKg),'sublabel' => 'Terpisah dari kas rupiah','accent' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Penerimaan Zakat (Beras)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formatKg($totalPenerimaanBerasKg)),'sublabel' => 'Terpisah dari kas rupiah','accent' => 'gold']); ?>
         <?php $__env->slot('icon', null, []); ?> 
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
            </svg>
         <?php $__env->endSlot(); ?>
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


<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Zakat Tersalurkan (Uang)','value' => $formatRupiah($totalDisalurkanUang),'sublabel' => 'Penyaluran berstatus selesai','accent' => 'emerald']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Zakat Tersalurkan (Uang)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formatRupiah($totalDisalurkanUang)),'sublabel' => 'Penyaluran berstatus selesai','accent' => 'emerald']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Zakat Tersalurkan (Beras)','value' => $formatKg($totalDisalurkanBerasKg),'sublabel' => 'Penyaluran berstatus selesai','accent' => 'gold']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Zakat Tersalurkan (Beras)','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formatKg($totalDisalurkanBerasKg)),'sublabel' => 'Penyaluran berstatus selesai','accent' => 'gold']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Stok Beras Saat Ini','value' => $formatKg($stokBerasSaatIniKg),'sublabel' => 'Saldo Kas Uang: ' . $formatRupiah($saldoKasUang),'accent' => 'teal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Stok Beras Saat Ini','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($formatKg($stokBerasSaatIniKg)),'sublabel' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Saldo Kas Uang: ' . $formatRupiah($saldoKasUang)),'accent' => 'teal']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['label' => 'Menunggu Verifikasi','value' => number_format($menungguVerifikasiCount, 0, ',', '.') . ' Transaksi','sublabel' => 'Perlu diperiksa oleh petugas/amil','accent' => 'amber']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Menunggu Verifikasi','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($menungguVerifikasiCount, 0, ',', '.') . ' Transaksi'),'sublabel' => 'Perlu diperiksa oleh petugas/amil','accent' => 'amber']); ?>
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


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div
        data-chart-box
        data-chart-target="chart-penerimaan"
        data-chart-payload='<?php echo json_encode($grafikPenerimaan, 15, 512) ?>'
        class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs"
    >
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Grafik Penerimaan Zakat</h3>
                <p class="text-xs text-slate-500">Akumulasi bulanan transaksi terverifikasi</p>
            </div>
            <div class="inline-flex rounded-lg p-1 bg-slate-100 gap-1">
                <button type="button" data-chart-unit="uang" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-[#1B4D3E] text-white cursor-pointer">Uang (Rp)</button>
                <button type="button" data-chart-unit="beras" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 cursor-pointer">Beras (Kg)</button>
            </div>
        </div>
        <div id="chart-penerimaan"></div>
    </div>

    <div
        data-chart-box
        data-chart-target="chart-penyaluran"
        data-chart-payload='<?php echo json_encode($grafikPenyaluranAsnaf, 15, 512) ?>'
        class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs"
    >
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Grafik Penyaluran per Asnaf</h3>
                <p class="text-xs text-slate-500">Distribusi selesai berdasarkan golongan asnaf</p>
            </div>
            <div class="inline-flex rounded-lg p-1 bg-slate-100 gap-1">
                <button type="button" data-chart-unit="uang" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-[#1B4D3E] text-white cursor-pointer">Uang (Rp)</button>
                <button type="button" data-chart-unit="beras" class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 cursor-pointer">Beras (Kg)</button>
            </div>
        </div>
        <div id="chart-penyaluran"></div>
    </div>
</div>


<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Transaksi Pembayaran Terbaru</h3>
                <p class="text-xs text-slate-500">6 pencatatan pembayaran zakat terakhir</p>
            </div>
            <?php if(\Illuminate\Support\Facades\Route::has('pembayaran.index')): ?>
                <a href="<?php echo e(route('pembayaran.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">Lihat Semua &rarr;</a>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Kode &amp; Tanggal</th>
                        <th class="px-5 py-3 font-semibold">Muzakki</th>
                        <th class="px-5 py-3 font-semibold">Jumlah</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-900"><?php echo e($trx->kode_transaksi); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($trx->tanggal_bayar?->format('d/m/Y H:i')); ?></div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-800"><?php echo e($trx->muzakki->nama_lengkap ?? '-'); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($trx->metodePembayaran->nama_metode ?? '-'); ?></div>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-900">
                                <?php if($trx->total_uang > 0): ?>
                                    <div><?php echo e($formatRupiah($trx->total_uang)); ?></div>
                                <?php endif; ?>
                                <?php if($trx->total_beras_kg > 0): ?>
                                    <div class="text-amber-700"><?php echo e($formatKg($trx->total_beras_kg)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php if (isset($component)) { $__componentOriginal435aefee4aa6dd7f20df034696ae03b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal435aefee4aa6dd7f20df034696ae03b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge-status','data' => ['status' => $trx->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trx->status)]); ?>
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
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-400">
                                Belum ada transaksi pembayaran zakat yang tercatat di database.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Aktivitas Terbaru</h3>
                <p class="text-xs text-slate-500">Jejak audit sistem real-time</p>
            </div>
            <?php if(auth()->user()->isAdmin() && \Illuminate\Support\Facades\Route::has('admin.aktivitas-log.index')): ?>
                <a href="<?php echo e(route('admin.aktivitas-log.index')); ?>" class="text-xs font-semibold text-[#1B4D3E] hover:underline">Log Lengkap</a>
            <?php endif; ?>
        </div>

        <div class="divide-y divide-slate-100 max-h-[380px] overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $aktivitasTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-5 py-3.5 hover:bg-slate-50/70">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold uppercase tracking-wide text-[#1B4D3E]"><?php echo e($log->modul); ?></span>
                        <span class="text-[11px] text-slate-400"><?php echo e($log->created_at?->diffForHumans()); ?></span>
                    </div>
                    <p class="text-xs text-slate-700 mt-1 leading-relaxed"><?php echo e($log->deskripsi); ?></p>
                    <p class="text-[11px] text-slate-400 mt-1">Oleh: <?php echo e($log->user->name ?? 'Sistem'); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-5 py-10 text-center text-sm text-slate-400">
                    Belum ada aktivitas pengguna yang tercatat.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\zakat-app\resources\views/dashboard/index.blade.php ENDPATH**/ ?>