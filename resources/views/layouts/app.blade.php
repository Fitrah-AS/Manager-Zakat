<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - Sistem Informasi Zakat')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F6F5] text-slate-800 antialiased min-h-screen">
    {{-- Backdrop mobile sidebar --}}
    <div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-slate-900/50 hidden lg:hidden"></div>

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Area Konten Utama --}}
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Konten --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-[1440px] w-full mx-auto">
                @include('components.alert')

                @yield('content')
            </main>

            <footer class="border-t border-slate-200 bg-white/70 px-6 py-3 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>Sistem Informasi Pengelolaan Zakat Fitrah &amp; Zakat Mal</span>
                <span>Zona Waktu: Asia/Jakarta (WIB)</span>
            </footer>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Global --}}
    @include('components.modal-confirm')

    @stack('scripts')
</body>
</html>
