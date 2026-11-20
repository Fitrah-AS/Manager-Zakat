@extends('layouts.app')

@section('title', 'Audit Log Aktivitas - Sistem Informasi Zakat')
@section('page_title', 'Audit Log Aktivitas Pengguna')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-200">
        <form method="GET" action="{{ route('admin.aktivitas-log.index') }}" class="flex flex-wrap items-center gap-2.5">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Cari deskripsi aktivitas..."
                class="w-full sm:w-64 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#1B4D3E] focus:outline-none"
            >
            <select name="modul" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Modul</option>
                @foreach ($moduls as $m)
                    <option value="{{ $m }}" @selected($modul === $m)>{{ $m }}</option>
                @endforeach
            </select>
            <select name="user_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">Semua Pengguna</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" @selected((string) $userId === (string) $u->id)>{{ $u->name }} ({{ $u->username }})</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-[#1B4D3E] hover:bg-[#0F5132] px-4 py-2 text-xs font-semibold text-white cursor-pointer">
                Terapkan Filter
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Waktu</th>
                    <th class="px-5 py-3 font-semibold">Pengguna</th>
                    <th class="px-5 py-3 font-semibold">Modul / Aksi</th>
                    <th class="px-5 py-3 font-semibold">Deskripsi</th>
                    <th class="px-5 py-3 font-semibold">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3 text-xs text-slate-600 whitespace-nowrap">
                            {{ $log->created_at?->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3 text-xs">
                            <div class="font-semibold text-slate-900">{{ $log->user->name ?? 'Sistem' }}</div>
                            @if ($log->user)
                                <div class="text-slate-500">&#64;{{ $log->user->username }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs">
                            <span class="inline-flex px-2 py-0.5 rounded bg-emerald-50 text-[#1B4D3E] font-semibold">{{ $log->modul }}</span>
                            <span class="ml-1 text-slate-500 uppercase">{{ $log->aksi }}</span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-700">{{ $log->deskripsi }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-slate-400">Belum ada catatan log aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100">{{ $logs->links() }}</div>
</div>
@endsection
