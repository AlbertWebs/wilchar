@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendLabel' => null,
    'bg' => 'bg-white',
])

<div {{ $attributes->class([$bg, 'rounded-2xl border border-slate-200/60 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md']) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $title }}</p>
            <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-100 text-slate-600">
                {!! $icon !!}
            </div>
        @endif
    </div>
    @if($trend)
        <div class="mt-4 flex items-center gap-2 text-sm">
            <span class="flex items-center gap-1 font-medium {{ $trend > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($trend > 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12l5-5 5 5M5 17h10" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 12l-5 5-5-5M19 7H9" />
                    @endif
                </svg>
                {{ number_format($trend, 1) }}%
            </span>
            @if($trendLabel)
                <span class="text-slate-500">{{ $trendLabel }}</span>
            @endif
        </div>
    @endif
</div>

