@php
    $siteName = config('app.name', 'Laravel');
    $title = 'Privacy Policy — '.$siteName;

    $data = require resource_path('data/articles.php');
    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => ['id' => $key, 'title' => $meta['title']])
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $slug = fn (string $s) => strtolower(preg_replace('/[^a-z0-9]+/i', '', $s));
    $domainName = $slug($siteName).'.com';

    $lastUpdated = \Carbon\Carbon::parse('2026-09-01');

    $sections = [
        [
            'heading' => 'Introduction',
            'body' => "Welcome to {$siteName}. This Privacy Policy explains how we collect, use, and protect information when you visit our website and use the account features we offer, such as our reader dashboard. By using {$siteName}, you agree to the practices described in this policy.",
        ],
        [
            'heading' => 'Information We Collect',
            'body' => "We collect only the information needed to operate {$siteName} and to provide the account features we offer:",
            'list' => [
                'Account information — if you create an account, we collect your name and email address, along with a securely hashed password.',
                'Usage data — we may collect general information about how you interact with our site, such as pages viewed and links clicked, to help us understand what content is useful.',
                'Technical data — like most websites, our servers automatically log standard technical details such as browser type, device type, and IP address for security and troubleshooting purposes.',
                'Communications — if you contact us directly, such as through our Contact page, we keep a record of that correspondence so we can respond to you.',
            ],
        ],
        [
            'heading' => 'How We Use Information',
            'body' => 'We use the information we collect to:',
            'list' => [
                'Provide, maintain, and secure your account and our website.',
                'Respond to questions, feedback, and correction requests you send us.',
                'Understand, in aggregate, how our articles and sections are used so we can improve them.',
                'Detect, investigate, and prevent fraudulent or unauthorized activity.',
                'Comply with applicable legal obligations.',
            ],
        ],
        [
            'heading' => 'Cookies',
            'body' => "{$siteName} may use a small number of essential cookies to keep you signed in and to remember basic site preferences. We do not use cookies to build advertising profiles, and we do not sell any information collected through cookies. You can configure your browser to refuse cookies, though some features of the site may not work as intended if you do.",
        ],
        [
            'heading' => 'Third-Party Links',
            'body' => "Our articles may reference or link to third-party websites, tools, or sources for further reading. We are not responsible for the privacy practices or content of those external sites, and we encourage you to review their own privacy policies before sharing any information with them.",
        ],
        [
            'heading' => 'Data Security',
            'body' => 'We take reasonable technical and organizational measures to protect the information we hold from loss, misuse, and unauthorized access. However, no method of transmission or storage over the internet is completely secure, and we cannot guarantee absolute security.',
        ],
        [
            'heading' => "Children's Privacy",
            'body' => "{$siteName} is intended for a general business and professional audience and is not directed at children. We do not knowingly collect personal information from children. If you believe a child has provided us with personal information, please contact us so we can remove it.",
        ],
        [
            'heading' => 'Changes to This Policy',
            'body' => "We may update this Privacy Policy from time to time to reflect changes in our practices or for legal or operational reasons. When we do, we will revise the date at the top of this page. We encourage you to review this policy periodically.",
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="Read the {{ $siteName }} privacy policy to learn how we collect, use, and protect your information." />
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
                        Legal
                    </span>
                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl dark:text-white">
                        Privacy Policy
                    </h1>
                    <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-500">
                        Last updated {{ $lastUpdated->format('F j, Y') }}
                    </p>
                </div>
            </section>

            <section class="mx-auto max-w-3xl px-6 py-14 lg:px-8">
                <div class="space-y-10">
                    @foreach ($sections as $block)
                        <div>
                            <h2 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                                {{ $block['heading'] }}
                            </h2>
                            <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">
                                {{ $block['body'] }}
                            </p>
                            @isset($block['list'])
                                <ul class="mt-4 space-y-2">
                                    @foreach ($block['list'] as $item)
                                        <li class="flex gap-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                                            <span class="mt-2 size-1.5 shrink-0 rounded-full bg-zinc-400 dark:bg-zinc-600"></span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </div>
                    @endforeach

                    <div>
                        <h2 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                            Contact Us
                        </h2>
                        <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">
                            If you have any questions about this Privacy Policy or how we handle your information, please reach out to us at
                            <a href="mailto:hello@{{ $domainName }}" class="font-medium text-zinc-900 hover:underline dark:text-white">hello@{{ $domainName }}</a>
                            or visit our <a href="{{ route('contact') }}" wire:navigate class="font-medium text-zinc-900 hover:underline dark:text-white">Contact page</a>.
                        </p>
                    </div>
                </div>

                <div class="mt-12 rounded-2xl border border-zinc-200 bg-zinc-50 p-6 text-sm leading-relaxed text-zinc-600 dark:border-zinc-800 dark:bg-zinc-900/40 dark:text-zinc-400">
                    <p>
                        {{ $siteName }} publishes educational and informational content only and is not a substitute for personalized financial, investment, tax, or legal advice. This policy describes our data practices and is not itself legal advice.
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
