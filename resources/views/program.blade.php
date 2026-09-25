{{--
    Program review page — a distinct article layout for in-depth looks at a
    single course/certification/degree program (as opposed to the standard
    editorial article in resources/views/article.blade.php).

    Content lives in resources/data/articles.php under the top-level
    'programs' key. Expects: $slug (route wildcard).
--}}
@php
    $siteName = config('app.name', 'Laravel');
    $data = require resource_path('data/articles.php');

    $program = collect($data['programs'])->firstWhere('slug', $slug);

    if (! $program) {
        abort(404);
    }

    $sectionMeta = $data['sections'][$program['section']];

    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => ['id' => $key, 'title' => $meta['title']])
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $relatedArticle = $program['related_slug']
        ? collect($data['articles'])->firstWhere('slug', $program['related_slug'])
        : null;

    $title = $program['title'].' — '.$siteName;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="{{ Str::limit($program['intro'], 155) }}" />
    </head>
    <body
        x-data="{ mobileOpen: false }"
        class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white dark:bg-zinc-950 dark:text-zinc-100 dark:selection:bg-white dark:selection:text-zinc-900"
    >
        @include('partials.site-header', ['categories' => $categories, 'siteName' => $siteName])

        <main>
            {{-- Breadcrumb --}}
            <div class="border-b border-zinc-200 dark:border-zinc-800">
                <div class="mx-auto flex max-w-3xl flex-wrap items-center gap-1.5 px-6 py-4 text-sm text-zinc-500 lg:px-8 dark:text-zinc-500">
                    <a href="{{ route('home') }}" wire:navigate class="hover:text-zinc-900 dark:hover:text-white">Home</a>
                    <flux:icon name="chevron-right" class="size-3.5" />
                    <a href="{{ route('section', $program['section']) }}" wire:navigate class="hover:text-zinc-900 dark:hover:text-white">{{ $sectionMeta['title'] }}</a>
                    <flux:icon name="chevron-right" class="size-3.5" />
                    <span class="min-w-0 truncate text-zinc-400 dark:text-zinc-600">{{ $program['title'] }}</span>
                </div>
            </div>

            <article class="mx-auto max-w-3xl px-6 py-10 lg:px-8 lg:py-14">
                <a href="{{ route('section', $program['section']) }}" wire:navigate class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1 text-xs font-medium tracking-wide text-zinc-600 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                    {{ $sectionMeta['title'] }} &middot; Program Guide
                </a>

                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-balance text-zinc-900 sm:text-4xl dark:text-white">
                    {{ $program['title'] }}
                </h1>

                <p class="mt-5 leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ $program['intro'] }}
                </p>

                {{-- CTA --}}
                <a
                    href="{{ $program['cta_url'] }}"
                    target="_blank"
                    rel="noopener noreferrer nofollow"
                    class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-6 py-3.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition hover:bg-orange-600 sm:w-auto"
                >
                    <flux:icon name="arrow-top-right-on-square" class="size-4" />
                    {{ strtoupper($program['cta_label']) }}
                </a>

                {{-- Hero panel --}}
                <div class="relative mt-8 flex h-56 items-center justify-center overflow-hidden rounded-2xl {{ $program['hero_image'] ?? null ? '' : $program['hero_gradient'] }} sm:h-72">
                    @if ($program['hero_image'] ?? null)
                        <img
                            src="{{ asset('images/'.$program['hero_image']) }}"
                            alt="{{ $program['hero_tagline'] }}"
                            class="absolute inset-0 size-full object-cover"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/85 via-zinc-950/30 to-zinc-950/10"></div>
                    @else
                        <div class="absolute -top-12 -right-12 size-48 rounded-full bg-white/10"></div>
                        <div class="absolute -bottom-16 -left-12 size-56 rounded-full bg-white/10"></div>
                        <div class="absolute top-1/4 right-1/5 size-20 rounded-full bg-white/10"></div>
                        <flux:icon name="{{ $program['hero_icon'] }}" class="absolute bottom-6 right-8 size-16 text-white/25 sm:size-24" />
                    @endif

                    <span class="absolute top-5 right-5 flex size-8 items-center justify-center rounded-md bg-white/95 text-zinc-900">
                        <x-app-logo-icon class="size-4 fill-current" />
                    </span>

                    <p class="relative max-w-md px-8 text-center text-xl font-semibold text-balance text-white drop-shadow-sm sm:text-2xl">
                        {{ $program['hero_tagline'] }}
                    </p>
                </div>

                {{-- Overview --}}
                <h2 class="mt-10 mb-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ $program['overview_heading'] }}
                </h2>
                <p class="leading-relaxed text-zinc-700 dark:text-zinc-300">
                    {{ $program['overview_intro'] }}
                </p>

                {{-- Feature list --}}
                <div class="mt-8 space-y-6">
                    @foreach ($program['features'] as $feature)
                        <div class="flex gap-4">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                                <flux:icon name="{{ $feature['icon'] }}" class="size-5" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $feature['title'] }}</h3>
                                <p class="mt-1.5 leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $feature['body'] }}</p>
                                @if ($feature['list'])
                                    <ul class="mt-3 grid gap-x-6 gap-y-1.5 text-sm text-zinc-600 sm:grid-cols-2 dark:text-zinc-400">
                                        @foreach ($feature['list'] as $item)
                                            <li class="flex items-center gap-2">
                                                <span class="size-1.5 shrink-0 rounded-full bg-zinc-400 dark:bg-zinc-600"></span>
                                                {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if ($feature['note'])
                                    <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $feature['note'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- CTA --}}
                <a
                    href="{{ $program['cta_url'] }}"
                    target="_blank"
                    rel="noopener noreferrer nofollow"
                    class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-6 py-3.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition hover:bg-orange-600 sm:w-auto"
                >
                    <flux:icon name="arrow-top-right-on-square" class="size-4" />
                    {{ strtoupper($program['cta_label']) }}
                </a>

                {{-- Pros --}}
                <div class="mt-10 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-800 dark:bg-zinc-900/40">
                    <h2 class="flex items-center gap-2 text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                        <flux:icon name="sparkles" class="size-5 text-orange-500" />
                        Pros
                    </h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($program['pros'] as $pro)
                            <li class="flex gap-3">
                                <flux:icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-500" />
                                <span class="leading-relaxed text-zinc-700 dark:text-zinc-300">
                                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pro['title'] }}</span>
                                    <span class="block text-sm text-zinc-500 dark:text-zinc-400">{{ $pro['description'] }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- CTA --}}
                <a
                    href="{{ $program['cta_url'] }}"
                    target="_blank"
                    rel="noopener noreferrer nofollow"
                    class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-6 py-3.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition hover:bg-orange-600 sm:w-auto"
                >
                    <flux:icon name="arrow-top-right-on-square" class="size-4" />
                    {{ strtoupper($program['cta_label']) }}
                </a>

                {{-- Extra sections --}}
                @foreach ($program['extra_sections'] as $section)
                    <h2 class="mt-10 mb-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                        {{ $section['heading'] }}
                    </h2>
                    @foreach ($section['paragraphs'] as $paragraph)
                        <p class="mt-5 leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $paragraph }}</p>
                    @endforeach

                    @if ($section['cta'])
                        <a
                            href="{{ $program['cta_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer nofollow"
                            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-full bg-orange-500 px-6 py-3.5 text-sm font-semibold text-white shadow-sm shadow-orange-500/20 transition hover:bg-orange-600 sm:w-auto"
                        >
                            <flux:icon name="arrow-top-right-on-square" class="size-4" />
                            {{ strtoupper($program['cta_label']) }}
                        </a>
                    @endif
                @endforeach

                {{-- Editorial team footer card --}}
                <div class="mt-14 flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-800 dark:bg-zinc-900/40">
                    <div class="flex items-center gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                            <x-app-logo-icon class="size-5 fill-current" />
                        </span>
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $siteName }} Editorial Team</p>
                    </div>
                    <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                        At {{ $siteName }}, we provide clear, research-driven information to help consumers and professionals better understand today's financial and business landscape. Our content covers topics ranging from financial strategies and market developments to business analytics, technology, leadership, and organizational management.
                    </p>
                    <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                        Our goal is to make complex topics easier to understand by presenting practical information, research, and established concepts in a clear and accessible format.
                    </p>
                    <flux:button href="{{ route('team') }}" wire:navigate variant="primary" class="mt-1 w-fit">
                        Learn More About Our Editorial Team
                    </flux:button>
                </div>
            </article>

            {{-- Related pillar article --}}
            @if ($relatedArticle)
                <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                    <div class="mx-auto max-w-3xl px-6 py-14 lg:px-8">
                        <h2 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                            See Also in {{ $sectionMeta['title'] }}
                        </h2>
                        <a href="{{ route('article', $relatedArticle['slug']) }}" wire:navigate class="group mt-6 block rounded-xl border border-zinc-200 bg-white p-5 transition hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:hover:border-zinc-700">
                            <p class="font-medium text-zinc-900 group-hover:underline dark:text-white">
                                {{ $relatedArticle['title'] }}
                            </p>
                        </a>
                    </div>
                </section>
            @endif
        </main>

        @include('partials.site-footer', ['siteName' => $siteName])

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
