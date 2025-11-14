@props([
    'title' => null,
    'description' => null,
    'action' => null,
])

<section {{ $attributes->class('rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm') }}>
    @if($title || $action)
        <header class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                @if($title)
                    <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                @endif
                @if($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>
            @if($action)
                <div class="flex items-center gap-2">
                    {{ $action }}
                </div>
            @endif
        </header>
    @endif

    <div class="pt-5">
        {{ $slot }}
    </div>
</section>

