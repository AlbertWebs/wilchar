@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendLabel' => null,
    'bg' => 'bg-white',
    'textColor' => 'text-slate-900',
])

@php
    $isGradient = str_contains($bg, 'gradient');
    $titleColor = $isGradient ? 'text-white/90' : 'text-slate-500';
    $valueColor = $isGradient ? 'text-white' : $textColor;
    $trendLabelColor = $isGradient ? 'text-white/70' : 'text-slate-500';
@endphp

<div {{ $attributes->class([$bg, 'rounded-2xl border border-slate-200/60 p-5 shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl']) }}>
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-xs font-medium uppercase tracking-wide {{ $titleColor }}">{{ $title }}</p>
            <p class="mt-3 text-2xl font-bold {{ $valueColor }}">{{ $value }}</p>
        </div>
        @if(isset($icon))
            {!! $icon !!}
        @endif
    </div>
    @if($trend)
        <div class="mt-4 flex items-center gap-2 text-sm">
            <span class="flex items-center gap-1 font-semibold {{ $trend > 0 ? ($isGradient ? 'text-white' : 'text-emerald-600') : ($isGradient ? 'text-white/80' : 'text-rose-500') }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($trend > 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l5-5 5 5M5 17h10" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12l-5 5-5-5M19 7H9" />
                    @endif
                </svg>
                {{ number_format($trend, 1) }}%
            </span>
            @if($trendLabel)
                <span class="{{ $trendLabelColor }}">{{ $trendLabel }}</span>
            @endif
        </div>
    @endif
</div>

