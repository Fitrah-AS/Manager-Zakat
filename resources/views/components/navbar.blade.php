@php
    $periodeAktifNavbar = \App\Models\Periode::aktif()->first();
@endphp

<header class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3 min-w-0">
        {{-- Tombol Hamburger Mobile --}}
        <button
            id="sidebar-open-btn"
            type="button"
            aria-label="Buka menu navigasi"
            class="lg:hidden p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="min-w-0">
            <h1 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                @yield('page_title', 'Dashboard')
            </h1>
            @if ($periodeAktifNavbar)
                <p class="text-xs text-slate-500 truncate">
                    Periode Aktif: <span class="font-semibold text-[#1B4D3E]">{{ $periodeAktifNavbar->nama_periode }} ({{ $periodeAktifNavbar->tahun_hijriah }})</span>
                </p>
            @else
                <p class="text-xs text-amber-600 font-medium">Belum ada periode zakat aktif</p>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        @if (\Illuminate\Support\Facades\Route::has('pembayaran.create'))
            <a
                href="{{ route('pembayaran.create') }}"
                class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition"
            >
                <svg class="w-4 h-4 text-[#E6C667]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Pembayaran</span>
            </a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white hover:bg-red-50 hover:border-red-200 hover:text-red-700 px-3 py-2 text-xs font-semibold text-slate-700 transition cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</header>
