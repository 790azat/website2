@php
    $siteName = config('app.name', 'Laravel');
    $data = require resource_path('data/articles.php');

    $article = collect($data['articles'])->firstWhere('slug', $slug);

    if (! $article) {
        abort(404);
    }

    $author = $data['authors'][$article['author']];
    $sectionMeta = $data['sections'][$article['section']];

    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => ['id' => $key, 'title' => $meta['title']])
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $publishedAt = \Carbon\Carbon::parse($article['date']);

    $relatedArticles = collect($data['articles'])
        ->where('section', $article['section'])
        ->where('slug', '!=', $article['slug'])
        ->sortByDesc('date')
        ->take(3)
        ->values();

    $title = $article['title'].' — '.$siteName;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="{{ Str::limit(strip_tags($article['body']), 155) }}" />
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
                    <a href="{{ route('section', $article['section']) }}" wire:navigate class="hover:text-zinc-900 dark:hover:text-white">{{ $sectionMeta['title'] }}</a>
                    <flux:icon name="chevron-right" class="size-3.5" />
                    <span class="min-w-0 truncate text-zinc-400 dark:text-zinc-600">{{ $article['title'] }}</span>
                </div>
            </div>

            {{-- Article header --}}
            <article class="mx-auto max-w-3xl px-6 py-10 lg:px-8 lg:py-14">
                <a href="{{ route('section', $article['section']) }}" wire:navigate class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1 text-xs font-medium tracking-wide text-zinc-600 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                    {{ $sectionMeta['title'] }}
                </a>

                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-balance text-zinc-900 sm:text-4xl dark:text-white">
                    {{ $article['title'] }}
                </h1>

                <div class="mt-6 flex items-center gap-3">
                    <img
                        src="{{ asset('images/team/'.$author['photo']) }}"
                        alt="{{ $author['name'] }}"
                        class="size-11 rounded-full object-cover ring-1 ring-zinc-200 dark:ring-zinc-800"
                    />
                    <div class="text-sm">
                        <a href="{{ route('team') }}" wire:navigate class="font-medium text-zinc-900 hover:underline dark:text-white">
                            {{ $author['name'] }}
                        </a>
                        <div class="flex items-center gap-1.5 text-zinc-500 dark:text-zinc-400">
                            <span>{{ $author['role'] }}</span>
                            <span class="text-zinc-300 dark:text-zinc-700">&middot;</span>
                            <time datetime="{{ $article['date'] }}">{{ $publishedAt->format('F j, Y') }}</time>
                        </div>
                    </div>
                </div>

                {{-- Hero image --}}
                @if ($article['image'] ?? null)
                    <div class="mt-8 overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-800">
                        <img
                            src="{{ asset('images/'.$article['image']) }}"
                            alt="{{ $article['title'] }}"
                            class="aspect-video w-full object-cover"
                        />
                    </div>
                @endif

                {{-- Body --}}
                @include('partials.article-body', ['body' => $article['body']])

                {{-- Author card / link to Our Editorial Team --}}
                <div class="mt-14 flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 sm:flex-row sm:items-center sm:justify-between dark:border-zinc-800 dark:bg-zinc-900/40">
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ asset('images/team/'.$author['photo']) }}"
                            alt="{{ $author['name'] }}"
                            class="size-14 rounded-full object-cover ring-1 ring-zinc-200 dark:ring-zinc-800"
                        />
                        <div>
                            <p class="font-medium text-zinc-900 dark:text-white">Written by {{ $author['name'] }}</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $author['role'] }} at {{ $siteName }}</p>
                        </div>
                    </div>
                    <flux:button href="{{ route('team') }}" wire:navigate variant="primary" class="shrink-0">
                        Meet Our Editorial Team
                    </flux:button>
                </div>
            </article>

            {{-- Related articles --}}
            @if ($relatedArticles->isNotEmpty())
                <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                    <div class="mx-auto max-w-5xl px-6 py-14 lg:px-8">
                        <h2 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-white">
                            More in {{ $sectionMeta['title'] }}
                        </h2>
                        <div class="mt-6 grid gap-6 sm:grid-cols-3">
                            @foreach ($relatedArticles as $related)
                                @php $relatedAuthor = $data['authors'][$related['author']]; @endphp
                                <a href="{{ route('article', $related['slug']) }}" wire:navigate class="group block rounded-xl border border-zinc-200 bg-white p-5 transition hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:hover:border-zinc-700">
                                    <p class="line-clamp-3 font-medium text-zinc-900 group-hover:underline dark:text-white">
                                        {{ $related['title'] }}
                                    </p>
                                    <p class="mt-3 text-xs text-zinc-500 dark:text-zinc-500">
                                        {{ $relatedAuthor['name'] }} &middot; {{ \Carbon\Carbon::parse($related['date'])->format('M j, Y') }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
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
