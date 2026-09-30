{{--
    Program review page: an in-depth look at a single course, certification,
    or degree program (distinct from the standard article layout).
    Content lives in resources/data/articles.php under 'programs'.
--}}
@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $program = SiteContent::program($slug);

    if (! $program) {
        abort(404);
    }

    $siteName = config('app.name', 'Laravel');
    $sectionMeta = SiteContent::section($program['section']);

    $relatedArticle = ($program['related_slug'] ?? null)
        ? SiteContent::article($program['related_slug'])
        : null;

    $title = $program['title'];
    $description = Str::limit($program['intro'], 155);
    $pageLocales = SiteContent::programLocales($program['slug']);
@endphp

@section('content')
    <article class="mx-auto max-w-3xl px-6 pt-8 pb-16 lg:px-8 lg:pt-12">
        {{-- Headline, then the lead text and button, then the cover image --}}
        <nav class="flex flex-wrap items-center justify-center gap-2 text-sm text-muted" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" wire:navigate class="font-medium hover:text-ink">{{ __('Home') }}</a>
            <span class="text-brand-500">/</span>
            <a href="{{ route('section', $program['section']) }}" wire:navigate class="font-medium hover:text-ink">{{ $sectionMeta['title'] ?? '' }}</a>
        </nav>

        <h1 class="mt-6 text-center font-display text-[1.75rem] leading-tight font-semibold tracking-tight text-balance text-ink sm:text-4xl lg:text-5xl">
            {{ $program['title'] }}
        </h1>
        <p class="mt-6 text-lg leading-relaxed text-body">{{ $program['intro'] }}</p>

        <a href="{{ $program['cta_url'] }}" target="_blank" rel="noopener noreferrer nofollow" class="btn-zest mt-8 w-full px-8 py-4 text-base">
            {{ $program['cta_label'] }}
            <flux:icon name="arrow-top-right-on-square" variant="mini" class="size-4" />
        </a>

        @if ($program['hero_image'])
            <div class="mt-8 mb-12 overflow-hidden rounded-[2rem]">
                <img src="{{ asset('images/'.$program['hero_image']) }}" alt="{{ $program['title'] }}" fetchpriority="high" class="aspect-video w-full object-cover" />
            </div>
        @else
            <div class="mb-12"></div>
        @endif

        @if (! empty($program['hero_tagline']))
            <p class="rounded-3xl bg-zest-200 p-6 font-display text-xl font-semibold text-balance text-brand-900">{{ $program['hero_tagline'] }}</p>
        @endif

        {{-- Overview --}}
        <h2 class="mt-12 font-display text-3xl leading-tight font-semibold tracking-tight text-ink">{{ $program['overview_heading'] }}</h2>
        <p class="mt-5 text-[1.075rem] leading-[1.85] text-body">{{ $program['overview_intro'] }}</p>

        {{-- Features --}}
        <div class="mt-10 space-y-5">
            @foreach ($program['features'] as $feature)
                <div class="flex gap-5 rounded-3xl border border-line bg-surface p-6">
                    <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-white dark:bg-brand-500 dark:text-brand-950">
                        <flux:icon name="{{ $feature['icon'] }}" class="size-6" />
                    </span>
                    <div class="min-w-0">
                        <h3 class="font-display text-xl font-semibold text-ink">{{ $feature['title'] }}</h3>
                        <p class="mt-2 leading-relaxed text-body">{{ $feature['body'] }}</p>
                        @if ($feature['list'])
                            <ul class="mt-4 grid gap-x-6 gap-y-2 text-sm text-body sm:grid-cols-2">
                                @foreach ($feature['list'] as $item)
                                    <li class="flex items-center gap-2">
                                        <flux:icon name="check" variant="micro" class="size-4 shrink-0 text-brand-500" />
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($feature['note'])
                            <p class="mt-4 text-sm leading-relaxed text-muted">{{ $feature['note'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pros --}}
        <div class="mt-12 rounded-3xl bg-brand-800 p-8">
            <h2 class="flex items-center gap-3 font-display text-2xl font-semibold text-white">
                <flux:icon name="sparkles" class="size-6 text-zest-400" />
                {{ __('Pros') }}
            </h2>
            <ul class="mt-6 space-y-5">
                @foreach ($program['pros'] as $pro)
                    <li class="flex gap-3.5">
                        <flux:icon name="check-circle" class="mt-0.5 size-6 shrink-0 text-zest-400" />
                        <span>
                            <span class="font-bold text-white">{{ $pro['title'] }}</span>
                            <span class="mt-1 block text-sm leading-relaxed text-brand-100">{{ $pro['description'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
            <a href="{{ $program['cta_url'] }}" target="_blank" rel="noopener noreferrer nofollow" class="btn-zest mt-8">
                {{ $program['cta_label'] }}
                <flux:icon name="arrow-top-right-on-square" variant="mini" class="size-4" />
            </a>
        </div>

        {{-- Extra sections --}}
        @foreach ($program['extra_sections'] ?? [] as $section)
            <h2 class="mt-14 font-display text-3xl leading-tight font-semibold tracking-tight text-ink">{{ $section['heading'] }}</h2>
            @foreach ($section['paragraphs'] as $paragraph)
                <p class="mt-5 text-[1.075rem] leading-[1.85] text-body">{{ $paragraph }}</p>
            @endforeach

            @if ($section['cta'])
                <a href="{{ $program['cta_url'] }}" target="_blank" rel="noopener noreferrer nofollow" class="btn-primary mt-7">
                    {{ $program['cta_label'] }}
                    <flux:icon name="arrow-top-right-on-square" variant="mini" class="size-4" />
                </a>
            @endif
        @endforeach

        {{-- Editorial team card --}}
        <div class="mt-16 rounded-3xl border border-line bg-surface p-7">
            <div class="flex items-center gap-3">
                @include('partials.logo', ['size' => 'sm'])
                <span class="text-sm font-semibold text-muted">{{ __('Editorial Team') }}</span>
            </div>
            <p class="mt-5 text-sm leading-relaxed text-body">
                {{ __('At :site, we provide clear, research-driven information to help consumers and professionals better understand today’s financial and business landscape, presenting practical information and established concepts in an accessible format.', ['site' => $siteName]) }}
            </p>
            <a href="{{ route('team') }}" wire:navigate class="btn-ghost mt-6">{{ __('Learn more about our editors') }}</a>
        </div>
    </article>

    {{-- Related pillar article --}}
    @if ($relatedArticle)
        <section class="border-t border-line bg-surface">
            <div class="mx-auto max-w-5xl px-6 py-16 lg:px-8">
                <span class="eyebrow">{{ __('See also') }}</span>
                <div class="mt-6">
                    @include('partials.article-card', ['article' => $relatedArticle, 'variant' => 'featured'])
                </div>
            </div>
        </section>
    @endif
@endsection
