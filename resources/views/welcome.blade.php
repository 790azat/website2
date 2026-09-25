@php
    $siteName = config('app.name', 'Laravel');
    $title = null;

    $data = require resource_path('data/articles.php');

    $sectionStyles = [
        'data-intelligence' => ['icon' => 'circle-stack', 'badge' => 'bg-sky-600', 'chip' => 'bg-gradient-to-r from-sky-500 to-blue-600', 'gradient' => 'bg-gradient-to-br from-sky-400 via-cyan-500 to-blue-600'],
        'business-strategy' => ['icon' => 'arrow-trending-up', 'badge' => 'bg-amber-600', 'chip' => 'bg-gradient-to-r from-amber-500 to-orange-600', 'gradient' => 'bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600'],
        'digital-horizons' => ['icon' => 'cpu-chip', 'badge' => 'bg-violet-600', 'chip' => 'bg-gradient-to-r from-violet-500 to-indigo-600', 'gradient' => 'bg-gradient-to-br from-violet-400 via-purple-500 to-indigo-600'],
        'people-impact' => ['icon' => 'user-group', 'badge' => 'bg-rose-600', 'chip' => 'bg-gradient-to-r from-rose-500 to-pink-600', 'gradient' => 'bg-gradient-to-br from-rose-400 via-pink-500 to-rose-600'],
    ];

    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => array_merge(
            ['id' => $key, 'title' => $meta['title']],
            $sectionStyles[$key]
        ))
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $allArticles = collect($data['articles'])->sortByDesc('date')->values();

    $attachMeta = function ($article) use ($data, $sectionStyles) {
        $article['author_info'] = $data['authors'][$article['author']];
        $article['section_title'] = $data['sections'][$article['section']]['title'];
        $article['style'] = $sectionStyles[$article['section']];
        return $article;
    };

    $featuredArticle = $allArticles->isNotEmpty() ? $attachMeta($allArticles->first()) : null;

    $latestArticles = $allArticles->slice(1, 5)->values()->map($attachMeta);

    $sidebarArticles = $allArticles->slice(6, 5)->values()->map($attachMeta);

    $programs = collect($data['programs'] ?? []);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="{{ $siteName }} publishes clear, research-driven guides on data intelligence, business strategy, digital horizons, and people & impact." />
    </head>
    <body
        x-data="{ mobileOpen: false }"
        class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white dark:bg-zinc-950 dark:text-zinc-100 dark:selection:bg-white dark:selection:text-zinc-900"
    >
        @include('partials.site-header', ['categories' => $categories, 'siteName' => $siteName])

        <main>
            {{-- Hero --}}
            <section class="relative overflow-hidden">
                <div class="pointer-events-none absolute inset-x-0 -top-40 -z-10 flex justify-center">
                    <div class="h-[26rem] w-[56rem] rounded-full bg-blue-200/40 blur-3xl dark:bg-blue-500/10"></div>
                </div>

                <div class="mx-auto max-w-4xl px-6 pt-16 pb-12 text-center lg:px-8 lg:pt-20">
                    <span class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1 text-xs font-medium tracking-wide text-zinc-600 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                        Research &amp; business education
                    </span>
                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-balance text-zinc-900 sm:text-5xl dark:text-white">
                        Welcome to {{ $siteName }}
                    </h1>
                    <p class="mt-6 text-lg leading-relaxed text-zinc-600 text-pretty dark:text-zinc-400">
                        We provide clear, research-driven information to help consumers and professionals better understand today&rsquo;s financial and business landscape.
                    </p>
                </div>

                {{-- Section chips --}}
                <div class="mx-auto flex max-w-5xl flex-wrap justify-center gap-3 px-6 pb-14 lg:px-8">
                    @foreach ($categories as $category)
                        <a
                            href="{{ route('section', $category['id']) }}"
                            wire:navigate
                            class="inline-flex items-center gap-2 rounded-full {{ $category['chip'] }} px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-black/5 transition hover:opacity-90 hover:shadow-md"
                        >
                            <flux:icon name="{{ $category['icon'] }}" class="size-4 text-white" />
                            {{ $category['title'] }}
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Articles: main list + sidebar --}}
            <section id="articles" class="mx-auto max-w-7xl scroll-mt-24 border-t border-zinc-200 px-6 py-14 lg:px-8 dark:border-zinc-800">
                <div class="grid gap-12 lg:grid-cols-3">
                    {{-- Main list --}}
                    <div class="min-w-0 lg:col-span-2">
                        <h2 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">Latest Articles</h2>

                        {{-- Featured article --}}
                        @if ($featuredArticle)
                        <a
                            href="{{ route('article', $featuredArticle['slug']) }}"
                            wire:navigate
                            class="group mt-6 flex flex-col overflow-hidden rounded-2xl border border-zinc-200 shadow-sm transition hover:shadow-md dark:border-zinc-800"
                        >
                            <div class="relative flex h-48 items-center justify-center overflow-hidden sm:h-64 {{ ($featuredArticle['image'] ?? null) ? '' : $featuredArticle['style']['gradient'] }}">
                                @if ($featuredArticle['image'] ?? null)
                                    <img
                                        src="{{ asset('images/'.$featuredArticle['image']) }}"
                                        alt="{{ $featuredArticle['title'] }}"
                                        class="absolute inset-0 size-full object-cover"
                                    />
                                    <div class="absolute inset-0 bg-zinc-950/20"></div>
                                @else
                                    <div class="absolute -top-10 -right-10 size-40 rounded-full bg-white/15"></div>
                                    <div class="absolute -bottom-14 -left-10 size-48 rounded-full bg-white/10"></div>
                                    <div class="absolute top-1/3 right-1/4 size-16 rounded-full bg-white/10"></div>
                                    <flux:icon name="{{ $featuredArticle['style']['icon'] }}" class="relative size-14 text-white drop-shadow sm:size-16" />
                                @endif
                            </div>
                            <div class="bg-white p-6 dark:bg-zinc-950 sm:p-8">
                                <span class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-[11px] font-semibold text-white {{ $featuredArticle['style']['badge'] }}">{{ $featuredArticle['section_title'] }}</span>
                                <p class="mt-3 text-xl font-semibold text-zinc-900 group-hover:underline sm:text-2xl dark:text-white">
                                    {{ $featuredArticle['title'] }}
                                </p>
                                <div class="mt-3 flex items-center gap-1.5 text-sm text-zinc-400 dark:text-zinc-500">
                                    <span>{{ $featuredArticle['author_info']['name'] }}</span>
                                    <span>&middot;</span>
                                    <time datetime="{{ $featuredArticle['date'] }}">{{ \Carbon\Carbon::parse($featuredArticle['date'])->format('M j, Y') }}</time>
                                </div>
                            </div>
                        </a>
                        @else
                            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">New articles are coming soon.</p>
                        @endif

                        <ul class="mt-8 divide-y divide-zinc-200 dark:divide-zinc-800">
                            @foreach ($latestArticles as $article)
                                <li class="min-w-0 py-5 first:pt-0">
                                    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group flex min-w-0 gap-4">
                                        <div class="relative flex h-20 w-32 shrink-0 items-center justify-center overflow-hidden rounded-lg shadow-sm {{ ($article['image'] ?? null) ? '' : $article['style']['gradient'] }} sm:h-24 sm:w-40">
                                            @if ($article['image'] ?? null)
                                                <img
                                                    src="{{ asset('images/'.$article['image']) }}"
                                                    alt="{{ $article['title'] }}"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="absolute inset-0 size-full object-cover"
                                                />
                                                <div class="absolute inset-0 bg-zinc-950/10"></div>
                                            @else
                                                <div class="absolute -top-5 -right-5 size-16 rounded-full bg-white/20"></div>
                                                <div class="absolute -bottom-6 -left-3 size-14 rounded-full bg-white/10"></div>
                                                <flux:icon name="{{ $article['style']['icon'] }}" class="relative size-7 text-white drop-shadow" />
                                            @endif
                                        </div>
                                        <div class="flex min-w-0 flex-col justify-center">
                                            <span class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-[11px] font-semibold text-white {{ $article['style']['badge'] }}">{{ $article['section_title'] }}</span>
                                            <p class="mt-1 line-clamp-2 font-medium text-zinc-900 group-hover:underline dark:text-white">
                                                {{ $article['title'] }}
                                            </p>
                                            <div class="mt-2 flex items-center gap-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                                                <span>{{ $article['author_info']['name'] }}</span>
                                                <span>&middot;</span>
                                                <time datetime="{{ $article['date'] }}">{{ \Carbon\Carbon::parse($article['date'])->format('M j, Y') }}</time>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="min-w-0 lg:col-span-1">
                        <h2 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">More to Read</h2>

                        <ul class="mt-6 space-y-4">
                            @foreach ($sidebarArticles as $article)
                                <li class="min-w-0">
                                    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group flex min-w-0 items-center gap-3">
                                        <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg shadow-sm {{ ($article['image'] ?? null) ? '' : $article['style']['gradient'] }}">
                                            @if ($article['image'] ?? null)
                                                <img
                                                    src="{{ asset('images/'.$article['image']) }}"
                                                    alt="{{ $article['title'] }}"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="absolute inset-0 size-full object-cover"
                                                />
                                                <div class="absolute inset-0 bg-zinc-950/10"></div>
                                            @else
                                                <div class="absolute -top-3 -right-3 size-9 rounded-full bg-white/20"></div>
                                                <flux:icon name="{{ $article['style']['icon'] }}" class="relative size-5 text-white drop-shadow" />
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <span class="inline-flex w-fit items-center rounded-full px-2 py-0.5 text-[10px] font-semibold text-white {{ $article['style']['badge'] }}">{{ $article['section_title'] }}</span>
                                            <p class="truncate font-medium text-zinc-900 group-hover:underline dark:text-white">{{ $article['title'] }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </section>

            {{-- Program guides --}}
            @if ($programs->isNotEmpty())
                <section class="border-t border-zinc-200 dark:border-zinc-800">
                    <div class="mx-auto max-w-6xl px-6 py-14 lg:px-8">
                        <div class="max-w-2xl">
                            <span class="inline-flex items-center rounded-full border border-orange-200 bg-orange-50 px-3 py-1 text-xs font-medium tracking-wide text-orange-700 uppercase dark:border-orange-900/40 dark:bg-orange-950/30 dark:text-orange-400">
                                Program Guides
                            </span>
                            <h2 class="mt-4 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                                In-Depth Course &amp; Program Reviews
                            </h2>
                            <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">
                                Detailed breakdowns of specific certifications and degree programs, including curriculum, cost, and what to expect.
                            </p>
                        </div>

                        <div class="mt-8 grid gap-6 sm:grid-cols-2">
                            @foreach ($programs as $program)
                                <a
                                    href="{{ route('program', $program['slug']) }}"
                                    wire:navigate
                                    class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 shadow-sm transition hover:shadow-md dark:border-zinc-800"
                                >
                                    <div class="relative flex h-32 items-center justify-center overflow-hidden {{ $program['hero_image'] ?? null ? '' : $program['hero_gradient'] }}">
                                        @if ($program['hero_image'] ?? null)
                                            <img
                                                src="{{ asset('images/'.$program['hero_image']) }}"
                                                alt="{{ $program['title'] }}"
                                                loading="lazy"
                                                decoding="async"
                                                class="absolute inset-0 size-full object-cover"
                                            />
                                            <div class="absolute inset-0 bg-zinc-950/40"></div>
                                        @else
                                            <div class="absolute -top-8 -right-8 size-32 rounded-full bg-white/10"></div>
                                            <div class="absolute -bottom-10 -left-8 size-36 rounded-full bg-white/10"></div>
                                            <flux:icon name="{{ $program['hero_icon'] }}" class="relative size-10 text-white drop-shadow" />
                                        @endif
                                    </div>
                                    <div class="flex flex-1 flex-col bg-white p-6 dark:bg-zinc-950">
                                        <p class="font-semibold text-zinc-900 group-hover:underline dark:text-white">
                                            {{ $program['title'] }}
                                        </p>
                                        <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-zinc-500 dark:text-zinc-400">
                                            {{ $program['intro'] }}
                                        </p>
                                        <span class="mt-4 inline-flex w-fit items-center gap-1.5 rounded-full bg-orange-500 px-4 py-2 text-xs font-semibold text-white transition group-hover:bg-orange-600">
                                            View Guide
                                            <flux:icon name="arrow-right" class="size-3.5" />
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            {{-- Approach strip --}}
            <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                <div class="mx-auto grid max-w-6xl gap-8 px-6 py-14 sm:grid-cols-3 lg:px-8">
                    @php
                        $values = [
                            ['icon' => 'academic-cap', 'title' => 'Research-driven', 'description' => 'Grounded in publicly available information and established business and financial concepts.', 'gradient' => 'bg-gradient-to-br from-sky-500 to-blue-600'],
                            ['icon' => 'eye', 'title' => 'Clear & transparent', 'description' => 'We explain the factors behind strategies and technologies, without the jargon.', 'gradient' => 'bg-gradient-to-br from-emerald-500 to-teal-600'],
                            ['icon' => 'shield-check', 'title' => 'Educational only', 'description' => 'Informational content, not personalized financial, tax, or legal advice.', 'gradient' => 'bg-gradient-to-br from-violet-500 to-indigo-600'],
                        ];
                    @endphp
                    @foreach ($values as $value)
                        <div class="text-center sm:text-left">
                            <div class="mx-auto flex size-10 items-center justify-center rounded-lg text-white shadow-sm sm:mx-0 {{ $value['gradient'] }}">
                                <flux:icon name="{{ $value['icon'] }}" class="size-5" />
                            </div>
                            <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">{{ $value['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $value['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Team jumbotron --}}
            <section class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-violet-600 to-rose-500">
                <div class="pointer-events-none absolute -left-24 -top-24 size-72 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -right-20 bottom-0 size-80 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute top-1/2 left-1/2 size-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-amber-400/10 blur-3xl"></div>

                <div class="relative mx-auto max-w-6xl px-6 py-16 text-center lg:px-8 lg:py-20">
                    <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-xs font-semibold tracking-wide text-white uppercase backdrop-blur">
                        Meet the Team
                    </span>
                    <h2 class="mt-5 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                        The People Behind {{ $siteName }}
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl leading-relaxed text-white/85">
                        Our team combines practical knowledge with research-driven insights to help you navigate financial and business decisions with greater confidence.
                    </p>

                    <div class="mt-10 flex justify-center">
                        <flux:button href="{{ route('team') }}" wire:navigate variant="primary" class="!bg-white !text-zinc-900 hover:!bg-white/90">
                            Meet the Full Team
                        </flux:button>
                    </div>
                </div>
            </section>
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
