@props([
    'label',
    'value',
    'sublabel' => null,
    'accent' => 'emerald',
])

@php
    $accentStyles = match ($accent) {
        'gold' => 'bg-amber-50 text-[#9A7B16] border-amber-200',
        'amber' => 'bg-orange-50 text-orange-700 border-orange-200',
        'teal' => 'bg-teal-50 text-teal-800 border-teal-200',
        default => 'bg-emerald-50 text-[#1B4D3E] border-emerald-200',
    };
@endphp

<div class="bg-white rounded-xl border border-slate-200/90 p-5 shadow-xs hover:shadow-sm transition">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-xl sm:text-2xl font-bold text-slate-900 tracking-tight break-words">{{ $value }}</p>
            @if ($sublabel)
                <p class="mt-1.5 text-xs text-slate-500">{{ $sublabel }}</p>
            @endif
        </div>
        @isset($icon)
            <div class="w-10 h-10 rounded-lg border flex items-center justify-center shrink-0 {{ $accentStyles }}">
                {{ $icon }}
            </div>
        @endisset
    </div>
</div>
