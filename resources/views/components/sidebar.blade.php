@php
    $user = auth()->user();
    $isAdmin = $user && $user->isAdmin();

    $navLink = function (string $routeName, string $label, string $activePattern) {
        $exists = \Illuminate\Support\Facades\Route::has($routeName);
        $url = $exists ? route($routeName) : '#';
        $isActive = $exists && request()->routeIs($activePattern);
        return compact('exists', 'url', 'isActive', 'label');
    };
@endphp

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-[#1B4D3E] text-white flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-out shadow-xl"
>
    {{-- Brand Header --}}
    <div class="h-16 px-5 flex items-center justify-between border-b border-white/10 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-white/10 border border-[#C9A227]/60 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#E6C667]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.364-6.364l-1.414 1.414M8.05 15.95l-1.414 1.414m0-11.314L8.05 8.05m7.9 7.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </div>
            <div>
                <div class="text-sm font-bold leading-tight tracking-tight">SIM ZAKAT</div>
                <div class="text-[11px] text-emerald-200/80">Fitrah &amp; Mal</div>
            </div>
        </a>

        <button
            id="sidebar-close-btn"
            type="button"
            aria-label="Tutup menu"
            class="lg:hidden p-1.5 rounded-lg text-emerald-100 hover:bg-white/10"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navigasi Menu --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6 text-sm">
        {{-- Menu Utama --}}
        <div>
            <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-200/70">Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#0F5132] text-white border-l-4 border-[#C9A227]' : 'text-emerald-100 hover:bg-white/10' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (\Illuminate\Support\Facades\Route::has('beranda'))
                    <li>
                        <a href="{{ route('beranda') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg text-emerald-100/90 hover:bg-white/10 transition">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3" />
                            </svg>
                            <span>Portal Publik</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Operasional Zakat (Admin & Petugas) --}}
        @php
            $operasionalItems = [
                $navLink('muzakki.index', 'Data Muzakki', 'muzakki.*'),
                $navLink('mustahik.index', 'Data Mustahik & Survei', 'mustahik.*'),
                $navLink('pembayaran.index', 'Pembayaran Zakat', 'pembayaran.*'),
                $navLink('verifikasi.index', 'Verifikasi Pembayaran', 'verifikasi.*'),
                $navLink('stok-beras.index', 'Stok Beras (Kg)', 'stok-beras.*'),
                $navLink('penyaluran.index', 'Penyaluran Zakat', 'penyaluran.*'),
                $navLink('laporan.index', 'Laporan & BKU', 'laporan.*'),
            ];
            $operasionalTersedia = collect($operasionalItems)->contains(fn ($i) => $i['exists']);
        @endphp

        @if ($operasionalTersedia)
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-200/70">Operasional Zakat</p>
                <ul class="space-y-1">
                    @foreach ($operasionalItems as $item)
                        @if ($item['exists'])
                            <li>
                                <a href="{{ $item['url'] }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition {{ $item['isActive'] ? 'bg-[#0F5132] text-white border-l-4 border-[#C9A227]' : 'text-emerald-100 hover:bg-white/10' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $item['isActive'] ? 'bg-[#C9A227]' : 'bg-emerald-300/60' }}"></span>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Menu Khusus Admin --}}
        @if ($isAdmin)
            @php
                $adminItems = [
                    $navLink('admin.periode.index', 'Periode & Tarif Fitrah', 'admin.periode.*'),
                    $navLink('admin.nisab.index', 'Standar Nisab Mal', 'admin.nisab.*'),
                    $navLink('admin.asnaf.index', 'Data Asnaf', 'admin.asnaf.*'),
                    $navLink('admin.jenis-zakat.index', 'Jenis Zakat', 'admin.jenis-zakat.*'),
                    $navLink('admin.metode-pembayaran.index', 'Metode Pembayaran', 'admin.metode-pembayaran.*'),
                    $navLink('admin.informasi.index', 'Informasi Lembaga', 'admin.informasi.*'),
                    $navLink('admin.users.index', 'Akun & Hak Akses', 'admin.users.*'),
                    $navLink('admin.aktivitas-log.index', 'Audit Log Aktivitas', 'admin.aktivitas-log.*'),
                ];
                $adminTersedia = collect($adminItems)->contains(fn ($i) => $i['exists']);
            @endphp

            @if ($adminTersedia)
                <div>
                    <p class="px-3 mb-2 text-[11px] font-semibold uppercase tracking-wider text-emerald-200/70">Administrasi &amp; Master</p>
                    <ul class="space-y-1">
                        @foreach ($adminItems as $item)
                            @if ($item['exists'])
                                <li>
                                    <a href="{{ $item['url'] }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium transition {{ $item['isActive'] ? 'bg-[#0F5132] text-white border-l-4 border-[#C9A227]' : 'text-emerald-100 hover:bg-white/10' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $item['isActive'] ? 'bg-[#C9A227]' : 'bg-emerald-300/60' }}"></span>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </nav>

    {{-- Profil Ringkas Bawah --}}
    @if ($user)
        <div class="p-4 border-t border-white/10 bg-[#153D31]">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#C9A227]/20 border border-[#C9A227]/60 text-[#E6C667] font-bold text-sm flex items-center justify-center shrink-0">
                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-emerald-200/80 truncate">{{ $user->role->label() }}</p>
                </div>
            </div>
        </div>
    @endif
</aside>
