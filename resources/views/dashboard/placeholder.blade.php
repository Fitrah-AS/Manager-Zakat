@extends('layouts.guest')

@section('title', 'Dashboard Sementara - Sistem Informasi Zakat')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-md border border-slate-200 p-8">
        @if (session('success'))
            <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <h1 class="text-lg font-bold text-[#1B4D3E]">Autentikasi Berhasil</h1>
                <p class="text-xs text-slate-500 mt-0.5">Tahap 3 selesai — Dashboard penuh dibangun pada Tahap 4</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-[#0F5132]">
                {{ auth()->user()->role->label() }}
            </span>
        </div>

        <div class="space-y-2 text-sm text-slate-700 mb-6">
            <p><span class="font-semibold">Nama:</span> {{ auth()->user()->name }}</p>
            <p><span class="font-semibold">Username:</span> {{ auth()->user()->username }}</p>
            <p><span class="font-semibold">Status Akun:</span> {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2.5 text-sm font-semibold text-white transition cursor-pointer"
            >
                Keluar Aplikasi (Logout)
            </button>
        </form>
    </div>
</div>
@endsection
