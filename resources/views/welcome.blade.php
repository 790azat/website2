@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $siteName = config('app.name', 'Laravel');
    $title = null;
    $description = __(':site publishes clear, research-driven guides to personal finance, wealth management, loans, and credit.', ['site' => $siteName]);

    $categories = SiteContent::categories();
    $allArticles = SiteContent::articles();
    $featuredArticle = $allArticles->first();
    $latestArticles = $allArticles->slice(1, 6)->values();
    $moreArticles = $allArticles->slice(7, 4)->values();
    $programs = SiteContent::programs();
    $authors = SiteContent::authors();

    // Hero card slides: one article with a cover image per slide, mixing
    // topics round-robin.
    $heroByTopic = $categories
        ->map(fn ($category) => SiteContent::articles($category['id'])->filter(fn ($article) => $article['image'])->values())
        ->filter(fn ($articles) => $articles->isNotEmpty())
        ->values();
    $heroPool = collect(range(0, max(0, ($heroByTopic->max(fn ($articles) => $articles->count()) ?? 0) - 1)))
        ->flatMap(fn (int $i) => $heroByTopic->map(fn ($articles) => $articles[$i] ?? null)->filter());
    if ($heroPool->isEmpty()) {
        $heroPool = $allArticles;
    }
    $heroSlides = $heroPool->take(8)->values();
@endphp

@section('content')
    {{-- Hero --}}
    <section class="relative z-10 overflow-x-clip">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(var(--color-line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:linear-gradient(to_bottom,black,transparent_85%)]"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 pt-14 pb-20 lg:grid-cols-12 lg:px-8 lg:pt-20 lg:pb-28">
            <div class="lg:col-span-6">
                <h1 class="font-display text-4xl leading-tight font-semibold tracking-tight text-ink sm:text-5xl">{{ __('Latest Guides') }}</h1>
                <ol class="mt-7 space-y-3">
                    @foreach ($allArticles->take(3) as $article)
                        <li>
                            <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group flex items-center gap-4 rounded-2xl border border-line bg-surface p-3 transition hover:border-brand-300 hover:bg-soft">
                                <span class="relative flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-xl">
                                    @include('partials.article-art', ['iconClass' => 'size-7'])
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-[11px] font-bold tracking-wide text-brand-700 uppercase dark:text-brand-300">{{ $article['section_title'] }}</span>
                                    <span class="mt-1 line-clamp-2 font-semibold leading-snug text-ink group-hover:text-brand-700 dark:group-hover:text-brand-300">{{ $article['title'] }}</span>
                                    <time datetime="{{ $article['date'] }}" class="mt-1 block text-xs text-muted">{{ \Carbon\Carbon::parse($article['date'])->translatedFormat(__('M j, Y')) }}</time>
                                </span>
                                <flux:icon name="arrow-up-right" variant="mini" class="size-4 shrink-0 text-muted transition group-hover:text-brand-600" />
                            </a>
                        </li>
                    @endforeach
                </ol>
                <p class="mt-7 max-w-xl text-lg leading-relaxed text-body">
                    {{ __(':site turns banking, investing, borrowing, and credit into practical lessons — researched carefully, written plainly, and free for everyone.', ['site' => $siteName]) }}
                </p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="#latest" class="btn-primary">
                        {{ __('Explore Top Guides') }}
                        <flux:icon name="arrow-down" variant="mini" class="size-4" />
                    </a>
                    <a href="{{ route('team') }}" wire:navigate class="btn-ghost">{{ __('Meet our editors') }}</a>
                </div>

                {{-- Article search: filters an inline index of this locale's articles as you type.
                     Results are real links rendered here (not built in JS) so the static export
                     rewrites them to the right path and language like every other link. --}}
                @php
                    $searchIndex = $allArticles->map(fn ($article) => mb_strtolower($article['title'].' '.$article['section_title'].' '.$article['excerpt']))->values();
                @endphp
                <div
                    x-data="{
                        query: '',
                        open: false,
                        texts: @js($searchIndex),
                        get results() {
                            const words = this.query.toLowerCase().split(/\s+/).filter(Boolean);
                            if (! words.length) return [];
                            const found = [];
                            for (let i = 0; i < this.texts.length && found.length < 6; i++) {
                                if (words.every(word => this.texts[i].includes(word))) found.push(i);
                            }
                            return found;
                        },
                    }"
                    @click.outside="open = false"
                    @keydown.escape="open = false"
                    class="relative mt-12 max-w-lg"
                >
                    <form role="search" @submit.prevent="if (results.length) $refs['result' + results[0]].click()">
                        <label for="hero-search" class="sr-only">{{ __('Search articles') }}</label>
                        <div class="relative">
                            <flux:icon name="magnifying-glass" variant="mini" class="pointer-events-none absolute top-1/2 left-5 size-5 -translate-y-1/2 text-muted" />
                            <input
                                id="hero-search"
                                type="search"
                                autocomplete="off"
                                x-model="query"
                                @focus="open = true"
                                @input="open = true"
                                placeholder="{{ __('Find guides on loans, credit, investing...') }}"
                                class="w-full rounded-full border border-line bg-surface py-4 pr-5 pl-13 text-base text-ink shadow-sm placeholder:text-muted focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none"
                            />
                        </div>
                    </form>

                    <div
                        x-cloak
                        x-show="open && query.trim() !== ''"
                        x-transition.opacity
                        class="absolute inset-x-0 top-full z-30 mt-2 flex flex-col overflow-hidden rounded-2xl border border-line bg-surface shadow-xl shadow-brand-900/10"
                    >
                        @foreach ($allArticles as $i => $article)
                            <a
                                href="{{ route('article', $article['slug']) }}"
                                x-ref="result{{ $i }}"
                                :style="{ order: results.indexOf({{ $i }}) }"
                                class="hidden border-b border-line px-5 py-3 hover:bg-soft focus:bg-soft focus:outline-none"
                                :class="{ 'hidden': ! results.includes({{ $i }}), 'block': results.includes({{ $i }}) }"
                            >
                                <span class="block text-[11px] font-bold tracking-wide text-brand-700 uppercase dark:text-brand-300">{{ $article['section_title'] }}</span>
                                <span class="mt-0.5 block text-sm font-semibold text-ink">{{ $article['title'] }}</span>
                            </a>
                        @endforeach
                        <p x-show="results.length === 0" class="px-5 py-4 text-sm text-muted">{{ __('No articles found.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Hero visual: learning-path card --}}
            <div class="relative lg:col-span-6">
                <div class="relative overflow-hidden rounded-[2.5rem] bg-brand-800 p-6 sm:p-10">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_10%,var(--color-brand-600),transparent_50%)]"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(var(--color-brand-600)_1px,transparent_1px)] [background-size:20px_20px] opacity-50"></div>
                    <div class="absolute -right-16 -bottom-16 size-64 rounded-full border-[28px] border-zest-400/20"></div>

                    <div class="relative rounded-3xl bg-surface p-6 shadow-2xl shadow-brand-950/30 sm:p-7">
                        <div class="flex items-center justify-between gap-4">
                            <p class="font-display text-2xl leading-tight font-semibold text-balance text-ink">{{ __('Trending topics') }}</p>
                            <span class="flex size-12 shrink-0 items-center justify-center rounded-2xl bg-zest-300 text-brand-900">
                                <flux:icon name="sparkles" class="size-6" />
                            </span>
                        </div>

                        {{-- Rotating article: one per slide; advances when the timer bar finishes. --}}
                        <div
                            x-data="{ active: 0, count: {{ $heroSlides->count() }}, go(i) { this.active = (i + this.count) % this.count } }"
                            class="hero-rotator mt-6"
                        >
                            <div class="grid grid-cols-1">
                                @foreach ($heroSlides as $s => $article)
                                    <a
                                        href="{{ route('article', $article['slug']) }}"
                                        wire:navigate
                                        class="group col-start-1 row-start-1 flex min-w-0 flex-col overflow-hidden rounded-2xl border border-line transition-all duration-500 ease-out hover:border-brand-300"
                                        @if ($s !== 0) x-cloak @endif
                                        :class="active === {{ $s }} ? 'visible translate-y-0 opacity-100' : 'invisible translate-y-2 opacity-0 pointer-events-none'"
                                        :aria-hidden="active !== {{ $s }}"
                                        :tabindex="active === {{ $s }} ? 0 : -1"
                                    >
                                        <span class="relative flex aspect-[16/9] items-center justify-center overflow-hidden">
                                            @include('partials.article-art', ['iconClass' => 'size-14'])
                                            <span class="absolute top-4 left-4 rounded-full bg-surface/95 px-2.5 py-1 text-[11px] font-bold tracking-wide text-brand-800 uppercase shadow-sm backdrop-blur dark:text-brand-200">
                                                {{ $article['section_title'] }}
                                            </span>
                                        </span>
                                        <span class="flex items-start gap-3 p-5">
                                            <span class="min-w-0 flex-1">
                                                <span class="line-clamp-2 font-display text-xl leading-snug font-semibold text-ink group-hover:text-brand-700 dark:group-hover:text-brand-300">{{ $article['title'] }}</span>
                                                <span class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted">{{ $article['excerpt'] }}</span>
                                            </span>
                                            <flux:icon name="arrow-up-right" variant="mini" class="mt-1 size-4 shrink-0 text-muted transition group-hover:text-brand-600" />
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                            @if ($heroSlides->count() > 1)
                                <div x-cloak class="mt-5 flex items-center gap-4">
                                    <span class="block h-1.5 flex-1 overflow-hidden rounded-full bg-soft">
                                        <span
                                            x-effect="active; $el.classList.remove('is-running'); void $el.offsetWidth; $el.classList.add('is-running')"
                                            @animationend="go(active + 1)"
                                            class="hero-timer block h-full rounded-full bg-brand-500"
                                        ></span>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        @foreach ($heroSlides as $s => $article)
                                            <button
                                                type="button"
                                                @click="go({{ $s }})"
                                                class="h-1.5 rounded-full transition-all"
                                                :class="active === {{ $s }} ? 'w-5 bg-brand-600' : 'w-1.5 bg-line hover:bg-brand-300'"
                                                aria-label="{{ __('Show articles :number', ['number' => $s + 1]) }}"
                                            ></button>
                                        @endforeach
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="relative mt-6 flex items-center gap-3 text-sm text-brand-100">
                        <span class="flex -space-x-2">
                            @foreach ($authors->take(4) as $author)
                                @include('partials.avatar', ['author' => $author, 'class' => 'size-9 text-xs'])
                            @endforeach
                        </span>
                        <span>{!! __('Written by :count in personal finance & wealth', ['count' => '<span class="font-semibold text-white">'.e(trans_choice(':count specialist|:count specialists', $authors->count())).'</span>']) !!}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Topics --}}
    <section class="border-y border-line bg-surface">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div class="max-w-2xl">
                    <span class="eyebrow">{{ __('Explore by topic') }}</span>
                    <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-balance text-ink">{{ __('Pick a subject and start learning') }}</h2>
                </div>
                <a href="{{ route('articles') }}" wire:navigate class="link-underline shrink-0 text-sm font-semibold text-ink">{{ __('Browse all articles') }}</a>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($categories as $i => $category)
                    <a href="{{ route('section', $category['id']) }}" wire:navigate class="group relative flex flex-col overflow-hidden rounded-3xl border border-line bg-paper p-7 transition duration-300 hover:-translate-y-1 hover:border-brand-700 hover:bg-brand-700">
                        <span class="font-display text-sm font-semibold text-muted transition group-hover:text-brand-200">0{{ $i + 1 }}</span>
                        <span class="mt-8 flex size-14 items-center justify-center rounded-2xl bg-brand-100 text-brand-700 transition group-hover:bg-zest-400 group-hover:text-brand-950 dark:bg-brand-900/60 dark:text-brand-200">
                            <flux:icon name="{{ $category['icon'] }}" class="size-7" />
                        </span>
                        <h3 class="mt-6 font-display text-2xl font-semibold text-ink transition group-hover:text-white">{{ $category['title'] }}</h3>
                        @if ($category['description'])
                            <p class="mt-2 text-sm leading-relaxed text-muted transition group-hover:text-brand-100">{{ $category['description'] }}</p>
                        @endif
                        <span class="mt-8 flex items-center justify-between text-sm font-semibold text-body transition group-hover:text-white">
                            {{ trans_choice(':count article|:count articles', $category['count']) }}
                            <flux:icon name="arrow-right" variant="mini" class="size-4 transition group-hover:translate-x-1" />
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest articles --}}
    <section id="latest" class="mx-auto max-w-7xl scroll-mt-28 px-6 py-20 lg:px-8">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div>
                <span class="eyebrow">{{ __('Fresh from the editors') }}</span>
                <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-ink">{{ __('Latest articles') }}</h2>
            </div>
            @if ($allArticles->isNotEmpty())
                <a href="{{ route('articles') }}" wire:navigate class="btn-ghost shrink-0">
                    {{ __('View all articles') }}
                    <flux:icon name="arrow-right" variant="mini" class="size-4" />
                </a>
            @endif
        </div>

        @if ($featuredArticle)
            <div class="mt-12">
                @include('partials.article-card', ['article' => $featuredArticle, 'variant' => 'featured'])
            </div>

            @if ($latestArticles->isNotEmpty())
                <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($latestArticles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @endforeach
                </div>
            @endif

            @if ($moreArticles->isNotEmpty())
                <div class="mt-16 rounded-3xl border border-line bg-surface p-7 sm:p-9">
                    <h3 class="font-display text-2xl font-semibold text-ink">{{ __('More to read') }}</h3>
                    <div class="mt-7 grid gap-6 md:grid-cols-2">
                        @foreach ($moreArticles as $article)
                            @include('partials.article-card', ['article' => $article, 'variant' => 'compact'])
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            @include('partials.empty-state')
        @endif
    </section>

    {{-- Program guides --}}
    @if ($programs->isNotEmpty())
        <section class="border-t border-line bg-soft">
            <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
                <div class="max-w-2xl">
                    <span class="eyebrow">{{ __('Lender guides') }}</span>
                    <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-ink">{{ __('In-depth lender & loan program reviews') }}</h2>
                    <p class="mt-4 leading-relaxed text-body">{{ __('Detailed breakdowns of specific lenders and financing programs, including loan options, prequalification, and what to expect.') }}</p>
                </div>

                <div class="mt-12 grid gap-8 md:grid-cols-2">
                    @foreach ($programs as $program)
                        <a href="{{ route('program', $program['slug']) }}" wire:navigate class="group card flex flex-col overflow-hidden transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-900/5">
                            <div class="relative flex aspect-video items-center justify-center overflow-hidden bg-brand-700">
                                @if ($program['hero_image'])
                                    <img src="{{ asset('images/'.$program['hero_image']) }}" alt="{{ $program['title'] }}" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105" />
                                @else
                                    <div class="absolute inset-0 bg-[radial-gradient(var(--color-brand-500)_1px,transparent_1px)] [background-size:18px_18px] opacity-40"></div>
                                    <flux:icon name="{{ $program['hero_icon'] ?? 'academic-cap' }}" class="relative size-12 text-white/90" />
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-7">
                                <h3 class="font-display text-xl font-semibold text-ink group-hover:text-brand-700 dark:group-hover:text-brand-300">{{ $program['title'] }}</h3>
                                <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-muted">{{ $program['intro'] }}</p>
                                <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-brand-700 dark:text-brand-300">
                                    {{ __('Read the guide') }}
                                    <flux:icon name="arrow-right" variant="mini" class="size-4 transition group-hover:translate-x-1" />
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- How we teach --}}
    <section class="border-t border-line bg-surface">
        <div class="mx-auto grid max-w-7xl gap-14 px-6 py-20 lg:grid-cols-12 lg:px-8">
            <div class="lg:col-span-4">
                <span class="eyebrow">{{ __('How we teach') }}</span>
                <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-balance text-ink">{{ __('Education first. Never a sales pitch.') }}</h2>
                <p class="mt-5 leading-relaxed text-body">{{ __('Every guide is built to help you understand a topic well enough to make your own decisions.') }}</p>
            </div>

            @php
                $steps = [
                    ['icon' => 'magnifying-glass', 'title' => __('Researched'), 'description' => __('Grounded in publicly available data, established concepts, and reputable sources.')],
                    ['icon' => 'light-bulb', 'title' => __('Explained plainly'), 'description' => __('Complex ideas broken into clear steps, with the jargon translated.')],
                    ['icon' => 'shield-check', 'title' => __('Independent'), 'description' => __('Informational content only — never personalized financial, tax, or legal advice.')],
                ];
            @endphp
            <div class="grid gap-5 sm:grid-cols-3 lg:col-span-8">
                @foreach ($steps as $i => $step)
                    <div class="rounded-3xl bg-paper p-7">
                        <div class="flex items-center justify-between">
                            <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-600 text-white dark:bg-brand-500 dark:text-brand-950">
                                <flux:icon name="{{ $step['icon'] }}" class="size-6" />
                            </span>
                            <span class="font-display text-4xl font-semibold text-line">0{{ $i + 1 }}</span>
                        </div>
                        <h3 class="mt-7 font-display text-xl font-semibold text-ink">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Team CTA --}}
    <section class="px-4 py-20 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2.5rem] bg-brand-700 px-8 py-16 sm:px-14 lg:py-20">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_0%_0%,var(--color-brand-500),transparent_45%),radial-gradient(circle_at_100%_100%,var(--color-brand-900),transparent_55%)]"></div>
            <div class="absolute -top-20 -right-20 size-80 rounded-full border-[40px] border-zest-400/20"></div>

            <div class="relative grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.16em] text-zest-300 uppercase">
                        <span class="h-px w-6 bg-current"></span> {{ __('Meet the team') }}
                    </span>
                    <h2 class="mt-5 font-display text-4xl leading-tight font-semibold text-balance text-white sm:text-5xl">
                        {{ __('Written by people who work with money every day') }}
                    </h2>
                    <p class="mt-5 max-w-lg leading-relaxed text-brand-100">
                        {{ __('Our editors combine hands-on experience in finance, data, and business with a commitment to clear, honest explanations.') }}
                    </p>
                    <a href="{{ route('team') }}" wire:navigate class="btn-zest mt-9">
                        {{ __('Meet the full team') }}
                        <flux:icon name="arrow-right" variant="mini" class="size-4" />
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach ($authors->take(4) as $author)
                        <div class="rounded-3xl bg-white/10 p-5 ring-1 ring-white/15 backdrop-blur">
                            @include('partials.avatar', ['author' => $author, 'class' => 'size-12 text-sm'])
                            <p class="mt-4 font-semibold text-white">{{ $author['name'] }}</p>
                            <p class="text-sm text-brand-200">{{ $author['role'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
