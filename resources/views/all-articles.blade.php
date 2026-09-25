@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $siteName = config('app.name', 'Laravel');
    $categories = SiteContent::categories();

    $selectedSection = request()->query('section');
    if ($selectedSection && ! $categories->contains('id', $selectedSection)) {
        $selectedSection = null;
    }

    $allArticles = SiteContent::articles($selectedSection);

    $perPage = 12;
    $totalArticles = $allArticles->count();
    $lastPage = max(1, (int) ceil($totalArticles / $perPage));
    $page = max(1, min((int) request()->query('page', 1), $lastPage));
    $pagedArticles = $allArticles->forPage($page, $perPage)->values();

    $pageLink = fn ($p) => route('articles', array_filter([
        'section' => $selectedSection,
        'page' => $p > 1 ? $p : null,
    ]));

    $title = 'All Articles';
    $description = 'Browse every guide published on '.$siteName.'.';

    $chip = fn (bool $active) => $active
        ? 'bg-brand-600 text-white border-brand-600 dark:bg-brand-500 dark:border-brand-500 dark:text-brand-950'
        : 'bg-surface text-body border-line hover:border-brand-400 hover:text-brand-700 dark:hover:text-brand-300';
@endphp

@section('content')
    @include('partials.page-hero', [
        'crumbs' => ['All Articles' => null],
        'eyebrow' => 'The library',
        'heading' => 'All articles',
        'lead' => 'Every guide we have published, newest first. Filter by topic to focus on what you want to learn next.',
        'meta' => $totalArticles.' '.Str::plural('article', $totalArticles),
        'icon' => 'book-open',
    ])

    <section class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('articles') }}" wire:navigate class="rounded-full border px-5 py-2.5 text-sm font-semibold transition {{ $chip(! $selectedSection) }}">
                All topics
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('articles', ['section' => $category['id']]) }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border px-5 py-2.5 text-sm font-semibold transition {{ $chip($selectedSection === $category['id']) }}">
                    <flux:icon name="{{ $category['icon'] }}" variant="mini" class="size-4" />
                    {{ $category['title'] }}
                </a>
            @endforeach
        </div>

        @if ($pagedArticles->isNotEmpty())
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pagedArticles as $article)
                    @include('partials.article-card', ['article' => $article])
                @endforeach
            </div>

            @include('partials.pagination')
        @else
            @include('partials.empty-state')
        @endif
    </section>
@endsection
