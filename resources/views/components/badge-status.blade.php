@props(['status'])

@php
    $value = is_object($status) && property_exists($status, 'value') ? $status->value : (string) $status;
    $label = is_object($status) && method_exists($status, 'label') ? $status->label() : ucwords(str_replace('_', ' ', $value));

    $classes = match ($value) {
        'terverifikasi', 'selesai', 'layak' => 'bg-emerald-100 text-[#0F5132] border-emerald-200',
        'menunggu_verifikasi', 'direncanakan', 'menunggu_survei', 'perlu_tinjauan' => 'bg-amber-100 text-amber-800 border-amber-200',
        'ditolak', 'dibatalkan', 'tidak_layak' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $classes }}">
    {{ $label }}
</span>
