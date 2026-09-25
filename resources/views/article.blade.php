@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $article = SiteContent::article($slug);

    if (! $article) {
        abort(404);
    }

    $siteName = config('app.name', 'Laravel');
    $author = $article['author_info'];
    $publishedAt = \Carbon\Carbon::parse($article['date']);

    $relatedArticles = SiteContent::articles($article['section'])
        ->where('slug', '!=', $article['slug'])
        ->take(3)
        ->values();

    $title = $article['title'];
    $description = Str::limit(strip_tags($article['body']), 155);
@endphp

@section('content')
    {{-- Article header --}}
    <section class="relative overflow-hidden border-b border-line">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(var(--color-line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>

        <div class="relative mx-auto max-w-4xl px-6 pt-10 pb-12 lg:px-8 lg:pb-16">
            <nav class="flex flex-wrap items-center gap-2 text-sm text-muted" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-1.5 font-medium hover:text-brand-700 dark:hover:text-brand-300">
                    <flux:icon name="home" variant="micro" class="size-4" /> Home
                </a>
                <span class="text-line">/</span>
                <a href="{{ route('section', $article['section']) }}" wire:navigate class="font-medium hover:text-brand-700 dark:hover:text-brand-300">{{ $article['section_title'] }}</a>
            </nav>

            <a href="{{ route('section', $article['section']) }}" wire:navigate class="tag mt-10">
                <flux:icon name="{{ $article['section_icon'] }}" variant="micro" class="size-3.5" />
                {{ $article['section_title'] }}
            </a>

            <h1 class="mt-5 font-display text-4xl leading-[1.08] font-semibold tracking-tight text-balance text-ink sm:text-5xl lg:text-6xl">
                {{ $article['title'] }}
            </h1>

            <div class="mt-9 flex flex-wrap items-center gap-x-8 gap-y-4 border-t border-line pt-6 text-sm">
                <a href="{{ route('team') }}" wire:navigate class="flex items-center gap-3">
                    @include('partials.avatar', ['author' => $author, 'class' => 'size-12 text-sm'])
                    <span>
                        <span class="block font-bold text-ink hover:text-brand-700 dark:hover:text-brand-300">{{ $author['name'] }}</span>
                        <span class="text-muted">{{ $author['role'] }}</span>
                    </span>
                </a>
                <span class="flex items-center gap-2 text-muted">
                    <flux:icon name="calendar" variant="mini" class="size-4 text-brand-500" />
                    <time datetime="{{ $article['date'] }}">{{ $publishedAt->format('F j, Y') }}</time>
                </span>
                <span class="flex items-center gap-2 text-muted">
                    <flux:icon name="clock" variant="mini" class="size-4 text-brand-500" />
                    {{ $article['reading_minutes'] }} min read
                </span>
            </div>
        </div>
    </section>

    <article class="mx-auto max-w-4xl px-6 lg:px-8">
        {{-- Hero image --}}
        @if ($article['image'])
            <div class="mt-12 overflow-hidden rounded-[2rem]">
                <img src="{{ asset('images/'.$article['image']) }}" alt="{{ $article['title'] }}" class="aspect-video w-full object-cover" />
            </div>
        @endif

        <div class="mx-auto max-w-3xl pt-6 pb-16">
            @include('partials.article-body', ['body' => $article['body']])

            {{-- Author card --}}
            <div class="relative mt-16 overflow-hidden rounded-3xl bg-brand-800 p-7 sm:p-9">
                <div class="absolute -top-12 -right-12 size-48 rounded-full border-[24px] border-zest-400/20"></div>
                <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        @include('partials.avatar', ['author' => $author, 'class' => 'size-16 text-lg'])
                        <div>
                            <p class="text-xs font-bold tracking-[0.16em] text-zest-300 uppercase">Written by</p>
                            <p class="mt-1 font-display text-2xl font-semibold text-white">{{ $author['name'] }}</p>
                            <p class="text-sm text-brand-200">{{ $author['role'] }} at {{ $siteName }}</p>
                        </div>
                    </div>
                    <a href="{{ route('team') }}" wire:navigate class="btn-zest shrink-0">Meet the editors</a>
                </div>
            </div>

            <p class="mt-8 rounded-2xl border border-line bg-surface p-5 text-sm leading-relaxed text-muted">
                <span class="font-semibold text-body">Educational content only.</span>
                This article is for general information and is not personalized financial, investment, tax, or legal advice.
            </p>
        </div>
    </article>

    {{-- Related articles --}}
    @if ($relatedArticles->isNotEmpty())
        <section class="border-t border-line bg-surface">
            <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <span class="eyebrow">Keep learning</span>
                        <h2 class="mt-3 font-display text-3xl font-semibold text-ink">More in {{ $article['section_title'] }}</h2>
                    </div>
                    <a href="{{ route('section', $article['section']) }}" wire:navigate class="link-underline text-sm font-semibold text-ink">See all</a>
                </div>
                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($relatedArticles as $related)
                        @include('partials.article-card', ['article' => $related])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
