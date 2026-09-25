{{--
    Header band for inner pages.
    Expects $heading; optional $eyebrow, $lead, $crumbs ([label => url|null]),
    $icon (Heroicon name) and $meta (small text line under the lead).
--}}
<section class="relative overflow-hidden border-b border-line">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(var(--color-line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>
    <div class="pointer-events-none absolute -top-24 right-0 size-96 rounded-full bg-brand-200/40 blur-3xl dark:bg-brand-700/20"></div>

    <div class="relative mx-auto max-w-7xl px-6 pt-10 pb-14 lg:px-8 lg:pt-12 lg:pb-20">
        @if (! empty($crumbs))
            <nav class="flex flex-wrap items-center gap-2 text-sm text-muted" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-1.5 font-medium hover:text-brand-700 dark:hover:text-brand-300">
                    <flux:icon name="home" variant="micro" class="size-4" /> Home
                </a>
                @foreach ($crumbs as $label => $url)
                    <span class="text-line">/</span>
                    @if ($url)
                        <a href="{{ $url }}" wire:navigate class="font-medium hover:text-brand-700 dark:hover:text-brand-300">{{ $label }}</a>
                    @else
                        <span class="min-w-0 truncate">{{ $label }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        <div class="mt-10 flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                @if (! empty($eyebrow))
                    <span class="eyebrow">{{ $eyebrow }}</span>
                @endif
                <h1 class="mt-4 font-display text-5xl leading-[1.05] font-semibold tracking-tight text-balance text-ink sm:text-6xl">
                    {{ $heading }}
                </h1>
                @if (! empty($lead))
                    <p class="mt-6 text-lg leading-relaxed text-body text-pretty">{{ $lead }}</p>
                @endif
                @if (! empty($meta))
                    <p class="mt-4 text-sm font-semibold text-muted">{{ $meta }}</p>
                @endif
            </div>

            @if (! empty($icon))
                <span class="hidden size-28 shrink-0 rotate-6 items-center justify-center rounded-[2rem] bg-brand-600 text-white shadow-xl shadow-brand-900/20 md:flex dark:bg-brand-500 dark:text-brand-950">
                    <flux:icon name="{{ $icon }}" class="size-12" />
                </span>
            @endif
        </div>
    </div>
</section>
