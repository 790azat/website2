@php
    $siteName = config('app.name', 'Laravel');
    $title = 'Contact — '.$siteName;

    $data = require resource_path('data/articles.php');
    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => ['id' => $key, 'title' => $meta['title']])
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $slug = fn (string $s) => strtolower(preg_replace('/[^a-z0-9]+/i', '', $s));
    $domainName = $slug($siteName).'.com';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="Get in touch with the {{ $siteName }} team." />
    </head>
    <body
        x-data="{ mobileOpen: false }"
        class="bg-white text-zinc-900 antialiased selection:bg-zinc-900 selection:text-white dark:bg-zinc-950 dark:text-zinc-100 dark:selection:bg-white dark:selection:text-zinc-900"
    >
        @include('partials.site-header', ['categories' => $categories, 'siteName' => $siteName])

        <main>
            <section class="relative overflow-hidden">
                <div class="pointer-events-none absolute inset-x-0 -top-40 -z-10 flex justify-center">
                    <div class="h-[26rem] w-[56rem] rounded-full bg-blue-200/40 blur-3xl dark:bg-blue-500/10"></div>
                </div>

                <div class="mx-auto max-w-2xl px-6 pt-16 pb-4 text-center lg:px-8 lg:pt-20">
                    <span class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1 text-xs font-medium tracking-wide text-zinc-600 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                        Contact
                    </span>
                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl dark:text-white">
                        Get in Touch
                    </h1>
                    <p class="mt-6 text-lg leading-relaxed text-zinc-600 text-pretty dark:text-zinc-400">
                        Have a question about an article, a correction to suggest, or feedback on {{ $siteName }}? We would like to hear from you.
                    </p>
                </div>
            </section>

            <section class="mx-auto max-w-3xl px-6 py-14 lg:px-8">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-950">
                        <div class="flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-blue-600">
                            <flux:icon name="envelope" class="size-5 text-white/95" />
                        </div>
                        <h2 class="mt-4 font-semibold text-zinc-900 dark:text-white">General Inquiries</h2>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Questions about our content, partnerships, or anything else.
                        </p>
                        <a href="mailto:hello@{{ $domainName }}" class="mt-3 inline-block text-sm font-medium text-zinc-900 hover:underline dark:text-white">
                            hello@{{ $domainName }}
                        </a>
                    </div>

                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-950">
                        <div class="flex size-10 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600">
                            <flux:icon name="pencil-square" class="size-5 text-white/95" />
                        </div>
                        <h2 class="mt-4 font-semibold text-zinc-900 dark:text-white">Editorial &amp; Corrections</h2>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            Spotted something that needs a closer look? Let our editorial team know.
                        </p>
                        <a href="mailto:editorial@{{ $domainName }}" class="mt-3 inline-block text-sm font-medium text-zinc-900 hover:underline dark:text-white">
                            editorial@{{ $domainName }}
                        </a>
                    </div>
                </div>

                <div class="mt-10 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 text-sm leading-relaxed text-zinc-600 dark:border-zinc-800 dark:bg-zinc-900/40 dark:text-zinc-400">
                    <p>
                        We aim to respond to every message within a few business days. {{ $siteName }} publishes educational and informational content only; we are not able to provide personalized financial, investment, tax, or legal advice through this contact channel.
                    </p>
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
