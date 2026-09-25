{{--
    Article card. Expects $article (from SiteContent) and optional
    $variant: 'grid' (default), 'featured' (wide hero card), or 'compact'
    (small row for sidebars / related lists).
--}}
@php $variant = $variant ?? 'grid'; @endphp

@if ($variant === 'featured')
    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group card grid overflow-hidden transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-900/5 lg:grid-cols-2">
        <div class="relative flex aspect-[16/10] items-center justify-center overflow-hidden lg:aspect-auto lg:min-h-96">
            @include('partials.article-art', ['iconClass' => 'size-20'])
        </div>
        <div class="flex flex-col justify-center p-7 sm:p-10">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-zest-400 px-2.5 py-1 text-[11px] font-bold tracking-wide text-brand-950 uppercase">Featured</span>
                <span class="tag">{{ $article['section_title'] }}</span>
            </div>
            <h3 class="mt-5 font-display text-2xl leading-tight font-semibold text-balance text-ink sm:text-3xl">
                {{ $article['title'] }}
            </h3>
            <p class="mt-4 line-clamp-3 leading-relaxed text-body">{{ $article['excerpt'] }}</p>
            <div class="mt-7 flex items-center gap-3 text-sm">
                @include('partials.avatar', ['author' => $article['author_info'], 'class' => 'size-10 text-sm'])
                <div>
                    <p class="font-semibold text-ink">{{ $article['author_info']['name'] }}</p>
                    <p class="text-muted">
                        <time datetime="{{ $article['date'] }}">{{ \Carbon\Carbon::parse($article['date'])->format('M j, Y') }}</time>
                        &middot; {{ $article['reading_minutes'] }} min read
                    </p>
                </div>
            </div>
        </div>
    </a>
@elseif ($variant === 'compact')
    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group flex min-w-0 items-center gap-4">
        <div class="relative flex size-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl">
            @include('partials.article-art', ['iconClass' => 'size-7'])
        </div>
        <div class="min-w-0">
            <p class="text-[11px] font-bold tracking-wide text-brand-700 uppercase dark:text-brand-300">{{ $article['section_title'] }}</p>
            <p class="mt-1 line-clamp-2 font-semibold leading-snug text-ink group-hover:text-brand-700 dark:group-hover:text-brand-300">{{ $article['title'] }}</p>
            <p class="mt-1 text-xs text-muted">{{ $article['reading_minutes'] }} min read</p>
        </div>
    </a>
@else
    <a href="{{ route('article', $article['slug']) }}" wire:navigate class="group card flex flex-col overflow-hidden transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-900/5">
        <div class="relative flex aspect-[16/10] items-center justify-center overflow-hidden">
            @include('partials.article-art')
            <span class="absolute top-4 left-4 rounded-full bg-surface/95 px-2.5 py-1 text-[11px] font-bold tracking-wide text-brand-800 uppercase shadow-sm backdrop-blur dark:text-brand-200">
                {{ $article['section_title'] }}
            </span>
        </div>
        <div class="flex flex-1 flex-col p-6">
            <h3 class="font-display text-xl leading-snug font-semibold text-balance text-ink group-hover:text-brand-700 dark:group-hover:text-brand-300">
                {{ $article['title'] }}
            </h3>
            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-muted">{{ $article['excerpt'] }}</p>
            <div class="mt-auto pt-6">
                <div class="flex items-center justify-between gap-3 border-t border-line pt-4 text-xs text-muted">
                    <span class="flex min-w-0 items-center gap-2">
                        @include('partials.avatar', ['author' => $article['author_info'], 'class' => 'size-7 text-[10px]'])
                        <span class="truncate font-semibold text-body">{{ $article['author_info']['name'] }}</span>
                    </span>
                    <time datetime="{{ $article['date'] }}" class="shrink-0">{{ \Carbon\Carbon::parse($article['date'])->format('M j, Y') }}</time>
                </div>
            </div>
        </div>
    </a>
@endif
