<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Portal Informasi Zakat Fitrah & Zakat Mal'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-[#F4F6F5] text-slate-800 antialiased min-h-screen flex flex-col">
    
    <header class="bg-[#1B4D3E] text-white border-b border-white/10 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <a href="<?php echo e(route('beranda')); ?>" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 border border-[#C9A227]/70 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#E6C667]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.364-6.364l-1.414 1.414M8.05 15.95l-1.414 1.414m0-11.314L8.05 8.05m7.9 7.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold tracking-tight">SIM ZAKAT</div>
                    <div class="text-[11px] text-emerald-200/90">Portal Transparansi Zakat Fitrah &amp; Mal</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-xs font-semibold text-emerald-100">
                <a href="<?php echo e(route('beranda')); ?>#fitrah" class="hover:text-[#F2D57E] transition">Zakat Fitrah</a>
                <a href="<?php echo e(route('beranda')); ?>#mal" class="hover:text-[#F2D57E] transition">Zakat Mal &amp; Nisab</a>
                <a href="<?php echo e(route('beranda')); ?>#statistik" class="hover:text-[#F2D57E] transition">Statistik Real-Time</a>
                <a href="<?php echo e(route('beranda')); ?>#lembaga" class="hover:text-[#F2D57E] transition">Profil Lembaga</a>
                <a href="<?php echo e(route('kalkulator.index')); ?>" class="hover:text-[#F2D57E] transition <?php echo e(request()->routeIs('kalkulator.*') ? 'text-[#F2D57E]' : ''); ?>">Kalkulator Zakat</a>
            </nav>

            <div class="flex items-center gap-2.5">
                <a href="<?php echo e(route('kalkulator.index')); ?>" class="hidden sm:inline-flex rounded-lg bg-[#C9A227] hover:bg-[#B8921F] px-3.5 py-2 text-xs font-bold text-slate-950 transition">
                    Kalkulator Zakat Mal
                </a>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 px-3.5 py-2 text-xs font-semibold text-white transition">
                        Dashboard Petugas
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="rounded-lg border border-white/25 bg-white/10 hover:bg-white/20 px-3.5 py-2 text-xs font-semibold text-white transition">
                        Masuk Petugas / Admin
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="bg-[#153D31] text-emerald-100/90 border-t border-white/10 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div>
                <p class="font-bold text-white">Sistem Informasi Pengelolaan Zakat Fitrah &amp; Zakat Mal</p>
                <p class="text-emerald-200/70 mt-0.5">Seluruh data statistik penerimaan dan penyaluran bersumber langsung dari catatan transaksi resmi terverifikasi.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('kalkulator.index')); ?>" class="hover:text-white underline">Kalkulator Zakat Mal</a>
                <a href="<?php echo e(route('login')); ?>" class="hover:text-white underline">Login Internal</a>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\laragon\www\zakat-app\resources\views/layouts/public.blade.php ENDPATH**/ ?>