@php
    $siteName = config('app.name', 'Laravel');
    $title = 'Our Editorial Team — '.$siteName;

    $data = require resource_path('data/articles.php');
    $categories = collect($data['sections'])
        ->map(fn ($meta, $key) => ['id' => $key, 'title' => $meta['title']])
        ->sortBy(fn ($c) => $data['sections'][$c['id']]['order'])
        ->values()
        ->all();

    $services = [
        ['icon' => 'banknotes', 'title' => 'Financial Strategies', 'accent' => 'text-sky-600 dark:text-sky-400', 'gradient' => 'bg-gradient-to-br from-sky-500 to-blue-600', 'description' => 'Educational insights into investing, risk management, financial planning, and market developments.'],
        ['icon' => 'presentation-chart-line', 'title' => 'Business Analytics', 'accent' => 'text-emerald-600 dark:text-emerald-400', 'gradient' => 'bg-gradient-to-br from-emerald-500 to-teal-600', 'description' => 'Information about data analysis, business intelligence, forecasting, and data-driven decision-making.'],
        ['icon' => 'arrow-trending-up', 'title' => 'Business Strategy', 'accent' => 'text-amber-600 dark:text-amber-400', 'gradient' => 'bg-gradient-to-br from-amber-500 to-orange-600', 'description' => 'Practical perspectives on growth, market positioning, customer retention, and organizational development.'],
        ['icon' => 'cpu-chip', 'title' => 'Technology & Innovation', 'accent' => 'text-violet-600 dark:text-violet-400', 'gradient' => 'bg-gradient-to-br from-violet-500 to-indigo-600', 'description' => 'Coverage of artificial intelligence, machine learning, cloud computing, cybersecurity, blockchain, and other business technologies.'],
        ['icon' => 'user-group', 'title' => 'Leadership & Organizations', 'accent' => 'text-rose-600 dark:text-rose-400', 'gradient' => 'bg-gradient-to-br from-rose-500 to-pink-600', 'description' => 'Insights into leadership, employee engagement, organizational resilience, collaboration, and workplace development.'],
        ['icon' => 'globe-alt', 'title' => 'Market & Business Insights', 'accent' => 'text-cyan-600 dark:text-cyan-400', 'gradient' => 'bg-gradient-to-br from-cyan-500 to-sky-600', 'description' => 'Research-based perspectives on economic developments, business trends, and factors that can influence organizations and consumers.'],
    ];

    $team = [
        ['key' => 'emily-carter', 'photo' => 'emily-carter.jpg', 'name' => 'Emily Carter', 'age' => 34, 'role' => 'Investment Consultant', 'bio' => 'Emily Carter, 34, is an investment consultant at '.$siteName.', specializing in emerging markets and risk management. She provides entrepreneurs with practical strategies to maximize returns, manage uncertainty, and protect long-term wealth.'],
        ['key' => 'james-mitchell', 'photo' => 'james-mitchell.jpg', 'name' => 'James Mitchell', 'age' => 36, 'role' => 'Financial Specialist', 'bio' => 'James Mitchell, 36, is a financial specialist at '.$siteName.', focused on navigating volatile markets and developing resilient investment strategies. He helps professionals build diversified portfolios designed to withstand market fluctuations and support sustainable growth.'],
        ['key' => 'michael-anderson', 'photo' => 'michael-anderson.jpg', 'name' => 'Michael Anderson', 'age' => 39, 'role' => 'Business and Data Analyst', 'bio' => 'Michael Anderson, 39, is a business and data analyst at '.$siteName.', focused on helping organizations turn complex information into practical business insights. His work covers data analysis, performance measurement, and data-driven decision-making.'],
        ['key' => 'daniel-brooks', 'photo' => 'daniel-brooks.jpg', 'name' => 'Daniel Brooks', 'age' => 37, 'role' => 'Financial Specialist', 'bio' => 'Daniel Brooks, 37, is a financial specialist at '.$siteName.', focused on market volatility and portfolio diversification. He helps professionals develop adaptable investment strategies designed to balance growth opportunities with prudent risk management.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <meta name="description" content="Meet the {{ $siteName }} team — professionals with experience across financial research, investment strategy, data analysis, business technology, and organizational strategy." />
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

                <div class="mx-auto max-w-3xl px-6 pt-16 pb-14 text-center lg:px-8 lg:pt-20">
                    <span class="inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-3 py-1 text-xs font-medium tracking-wide text-zinc-600 uppercase dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400">
                        Our Editorial Team
                    </span>
                    <h1 class="mt-6 text-4xl font-semibold tracking-tight text-balance text-zinc-900 sm:text-5xl dark:text-white">
                        Welcome to {{ $siteName }}
                    </h1>
                    <p class="mt-6 text-lg leading-relaxed text-zinc-600 text-pretty dark:text-zinc-400">
                        At {{ $siteName }}, we provide clear, research-driven information to help consumers and professionals better understand today&rsquo;s financial and business landscape. Our content covers topics ranging from financial strategies and market developments to business analytics, technology, leadership, and organizational management.
                    </p>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        Our goal is to make complex topics easier to understand by presenting practical information, research, and established concepts in a clear and accessible format.
                    </p>
                </div>
            </section>

            {{-- Our Mission --}}
            <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                <div class="mx-auto max-w-3xl px-6 py-14 text-center lg:px-8">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Our Mission</h2>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        Our mission is to provide accessible, practical information that helps readers better understand financial, business, and organizational topics. We focus on clarity, transparency, and useful insights that can be applied to real-world situations.
                    </p>
                </div>
            </section>

            {{-- What We Do --}}
            <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">What We Do</h2>
                    <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        We research and publish educational resources covering a range of topics, including:
                    </p>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <div class="rounded-2xl border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-950">
                            <div class="flex size-10 items-center justify-center rounded-lg {{ $service['gradient'] }}">
                                <flux:icon name="{{ $service['icon'] }}" class="size-5 text-white/95" />
                            </div>
                            <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">{{ $service['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $service['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Our Approach --}}
            <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                <div class="mx-auto max-w-3xl px-6 py-14 lg:px-8">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Our Approach</h2>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        We believe information should be clear, transparent, and easy to evaluate. Our content is based on publicly available information, research, established concepts, and practical business knowledge.
                    </p>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        We aim to explain complex subjects without unnecessary jargon or misleading claims. Where appropriate, our articles discuss different factors, potential benefits, challenges, and considerations so readers can develop a better understanding of the subject.
                    </p>
                    <p class="mt-4 rounded-xl border border-zinc-200 bg-white p-4 text-sm leading-relaxed text-zinc-500 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-500">
                        Our content is intended for educational and informational purposes and should not be considered personalized financial, investment, tax, legal, or professional advice.
                    </p>
                </div>
            </section>

            {{-- Team grid --}}
            <section id="team" class="mx-auto max-w-6xl scroll-mt-24 px-6 py-16 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Our Editorial Team</h2>
                    <p class="mt-3 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        Our team brings together professionals with experience across financial research, investment strategy, data analysis, business technology, and organizational strategy. Their combined expertise supports the range of topics covered throughout {{ $siteName }}.
                    </p>
                </div>

                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($team as $member)
                        <div class="text-center">
                            <img
                                src="{{ asset('images/team/'.$member['photo']) }}"
                                alt="{{ $member['name'] }}"
                                class="mx-auto size-28 rounded-full object-cover ring-1 ring-zinc-200 dark:ring-zinc-800"
                            />
                            <h3 class="mt-4 font-semibold text-zinc-900 dark:text-white">{{ $member['name'] }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $member['role'] }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $member['bio'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Our Editorial Focus --}}
            <section class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900/40">
                <div class="mx-auto max-w-3xl px-6 py-14 lg:px-8">
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Our Editorial Focus</h2>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        The expertise of our contributors allows {{ $siteName }} to cover topics from multiple professional perspectives. Financial topics are examined alongside developments in business strategy, technology, data, and organizational leadership.
                    </p>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        This multidisciplinary approach helps us present information in context rather than treating individual financial or business decisions in isolation.
                    </p>
                    <p class="mt-4 leading-relaxed text-zinc-600 dark:text-zinc-400">
                        We also aim to keep our content practical. Articles focus on explaining concepts, identifying important considerations, and helping readers understand how different strategies and developments can affect businesses, professionals, and consumers.
                    </p>
                </div>
            </section>

            {{-- Thank you / CTA --}}
            <section class="mx-auto max-w-5xl px-6 py-16 text-center lg:px-8">
                <div class="rounded-3xl bg-zinc-900 px-8 py-14 dark:bg-white">
                    <h2 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl dark:text-zinc-900">
                        Thank You for Visiting {{ $siteName }}
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl leading-relaxed text-zinc-300 dark:text-zinc-600">
                        We are committed to providing useful, transparent, and research-driven information that helps readers better understand financial, business, technology, and organizational topics. We will continue developing educational resources designed to make complex subjects easier to understand and evaluate.
                    </p>
                    <div class="mt-8 flex justify-center">
                        <flux:button href="{{ route('home') }}" wire:navigate variant="primary" class="dark:!bg-zinc-900 dark:!text-white">
                            Explore Our Articles
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
