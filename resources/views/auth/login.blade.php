@extends('layouts.guest')

@section('title', 'Masuk Aplikasi - Sistem Pengelolaan Zakat')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10 bg-gradient-to-br from-[#1B4D3E] via-[#0F5132] to-[#13382C]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl border border-emerald-900/10 overflow-hidden">
            {{-- Header --}}
            <div class="px-8 pt-8 pb-6 bg-[#1B4D3E] text-white text-center relative">
                <div class="mx-auto mb-4 w-14 h-14 rounded-xl bg-white/10 border border-[#C9A227]/50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#E6C667]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m14.364-6.364l-1.414 1.414M8.05 15.95l-1.414 1.414m0-11.314L8.05 8.05m7.9 7.9l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold tracking-tight">Sistem Informasi Zakat</h1>
                <p class="text-sm text-emerald-100/90 mt-1">Pengelolaan Zakat Fitrah &amp; Zakat Mal</p>
                <div class="mt-4 h-0.5 w-16 mx-auto bg-[#C9A227] rounded-full"></div>
            </div>

            {{-- Body Form --}}
            <div class="p-8">
                @if (session('success'))
                    <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" data-loading-form class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Username
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username Anda"
                            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#1B4D3E] focus:outline-none focus:ring-2 focus:ring-[#1B4D3E]/20"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password Anda"
                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 pr-11 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#1B4D3E] focus:outline-none focus:ring-2 focus:ring-[#1B4D3E]/20"
                            >
                            <button
                                type="button"
                                data-toggle-password="password"
                                aria-label="Tampilkan atau sembunyikan password"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600"
                            >
                                <svg data-icon-show class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg data-icon-hide class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[#1B4D3E] focus:ring-[#1B4D3E]">
                            <span>Ingat sesi saya</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        data-loading-text="MEMPROSES..."
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] active:bg-[#0B3D26] px-4 py-3 text-sm font-bold tracking-wide text-white shadow-sm transition cursor-pointer"
                    >
                        <svg data-submit-spinner class="hidden animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span data-submit-label>MASUK APLIKASI</span>
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                    <a href="{{ route('beranda') }}" class="text-xs font-medium text-[#1B4D3E] hover:underline">
                        &larr; Kembali ke Halaman Publik
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
