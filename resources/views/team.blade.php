@extends('layouts.site')
@use('App\Support\SiteContent')

@php
    $siteName = config('app.name', 'Laravel');
    $title = __('Our Editorial Team');
    $description = __('Meet the :site team — writers and analysts covering personal finance, wealth management, lending, and credit.', ['site' => $siteName]);

    $team = SiteContent::authors();

    $services = [
        ['icon' => 'wallet', 'title' => __('Banking & Budgeting'), 'description' => __('Checking and savings accounts, high-yield options, online banking, and budgets that hold up in real life.')],
        ['icon' => 'shield-check', 'title' => __('Insurance & Taxes'), 'description' => __('Auto, life, and health coverage, Medicare, deductions, credits, and options for addressing tax debt.')],
        ['icon' => 'chart-pie', 'title' => __('Investing & Retirement'), 'description' => __('Portfolio allocation, retirement accounts, and long-term strategies for growing and protecting wealth.')],
        ['icon' => 'building-library', 'title' => __('Loans & Mortgages'), 'description' => __('Mortgages, personal and business loans, and how rates, fees, and terms shape the true cost of borrowing.')],
        ['icon' => 'credit-card', 'title' => __('Credit & Cards'), 'description' => __('Credit scores, rewards and travel cards, annual fees, and managing debt with a clear plan.')],
        ['icon' => 'briefcase', 'title' => __('Small Business Finance'), 'description' => __('Business bank accounts, cash flow, financing, and the practical money decisions owners face.')],
    ];

    $principles = [
        ['title' => __('Clear'), 'text' => __('We explain complex subjects without unnecessary jargon or misleading claims.')],
        ['title' => __('Transparent'), 'text' => __('Our content is based on publicly available information, research, and established concepts.')],
        ['title' => __('Balanced'), 'text' => __('Where it matters, we discuss benefits, challenges, and trade-offs so you can weigh them yourself.')],
    ];
@endphp

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-line">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(var(--color-line)_1px,transparent_1px)] [background-size:26px_26px] [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-6 py-16 lg:grid-cols-2 lg:px-8 lg:py-24">
            <div>
                <span class="eyebrow">{{ __('Our editorial team') }}</span>
                <h1 class="mt-5 font-display text-5xl leading-[1.04] font-semibold tracking-tight text-balance text-ink sm:text-6xl">
                    {{ __('The people who make :site', ['site' => $siteName]) }} <span class="italic text-brand-600 dark:text-brand-400">{{ __('make sense.') }}</span>
                </h1>
                <p class="mt-7 text-lg leading-relaxed text-body">
                    {{ __('At :site, we provide clear, research-driven information to help consumers and professionals make better money decisions — from everyday banking and budgeting to investing, borrowing, and credit.', ['site' => $siteName]) }}
                </p>
                <a href="#team" class="btn-primary mt-9">
                    {{ __('Meet the editors') }}
                    <flux:icon name="arrow-down" variant="mini" class="size-4" />
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach ($team->take(4) as $i => $member)
                    <div @class([
                        'rounded-3xl p-6',
                        'bg-brand-700 text-white' => $i === 0 || $i === 3,
                        'bg-surface border border-line' => $i === 1 || $i === 2,
                        'translate-y-6' => $i % 2 === 1,
                    ])>
                        @include('partials.avatar', ['author' => $member, 'class' => 'size-14 text-base'])
                        <p @class(['mt-5 font-display text-lg font-semibold', 'text-white' => $i === 0 || $i === 3, 'text-ink' => $i === 1 || $i === 2])>{{ $member['name'] }}</p>
                        <p @class(['text-sm', 'text-brand-200' => $i === 0 || $i === 3, 'text-muted' => $i === 1 || $i === 2])>{{ $member['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mission --}}
    <section class="bg-brand-800">
        <div class="mx-auto max-w-5xl px-6 py-20 text-center lg:px-8">
            <span class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.16em] text-zest-300 uppercase">{{ __('Our mission') }}</span>
            <p class="mt-6 font-display text-3xl leading-snug font-medium text-balance text-white sm:text-4xl">
                &ldquo;{{ __('To provide accessible, practical information that helps readers understand financial, business, and organizational topics — and apply it to real-world decisions.') }}&rdquo;
            </p>
        </div>
    </section>

    {{-- What we cover --}}
    <section class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
        <div class="max-w-2xl">
            <span class="eyebrow">{{ __('What we cover') }}</span>
            <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-ink">{{ __('Educational resources across six areas') }}</h2>
        </div>

        <div class="mt-12 grid gap-px overflow-hidden rounded-3xl border border-line bg-line sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($services as $service)
                <div class="group bg-surface p-8 transition hover:bg-soft">
                    <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-100 text-brand-700 transition group-hover:bg-brand-600 group-hover:text-white dark:bg-brand-900/60 dark:text-brand-200">
                        <flux:icon name="{{ $service['icon'] }}" class="size-6" />
                    </span>
                    <h3 class="mt-6 font-display text-xl font-semibold text-ink">{{ $service['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $service['description'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Team grid --}}
    <section id="team" class="scroll-mt-28 border-y border-line bg-surface">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span class="eyebrow">{{ __('The editors') }}</span>
                <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-ink">{{ __('Experience you can learn from') }}</h2>
                <p class="mt-4 leading-relaxed text-body">
                    {{ __('Our writers and analysts bring experience across consumer banking, credit, lending, wealth planning, and small-business finance.') }}
                </p>
            </div>

            <div class="mx-auto mt-14 max-w-4xl rounded-3xl border border-line bg-paper p-8 sm:p-10">
                <h3 class="font-display text-2xl font-semibold text-ink">{{ __(':site Editorial Team', ['site' => $siteName]) }}</h3>
                <p class="mt-4 leading-relaxed text-body">
                    {{ __('The :site editorial team brings together writers and analysts with experience across personal finance, consumer credit, banking, wealth management, small business, and financial technology. Our contributors focus on clear, practical explanations of financial topics, combining research with real-world considerations to help readers understand products, costs, requirements, and long-term financial decisions without unnecessary jargon.', ['site' => $siteName]) }}
                </p>
            </div>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($team as $member)
                    <div class="flex flex-col rounded-3xl border border-line bg-paper p-7">
                        @include('partials.avatar', ['author' => $member, 'class' => 'size-20 text-xl'])
                        <h3 class="mt-6 font-display text-xl font-semibold text-ink">{{ $member['name'] }}</h3>
                        <p class="mt-1 text-sm font-semibold text-brand-700 dark:text-brand-300">{{ $member['role'] }}</p>
                        @if (! empty($member['bio']))
                            <p class="mt-4 text-sm leading-relaxed text-muted">{{ $member['bio'] }}</p>
                        @endif
                        @if ($member['count'])
                            <p class="mt-auto pt-5 text-xs font-bold tracking-wide text-brand-700 uppercase dark:text-brand-300">
                                {{ trans_choice(':count article|:count articles', $member['count']) }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Approach --}}
    <section class="mx-auto grid max-w-7xl gap-14 px-6 py-20 lg:grid-cols-2 lg:px-8">
        <div>
            <span class="eyebrow">{{ __('Our approach') }}</span>
            <h2 class="mt-4 font-display text-4xl font-semibold tracking-tight text-balance text-ink">{{ __('Information should be easy to evaluate') }}</h2>
            <p class="mt-5 leading-relaxed text-body">
                {{ __('We present information in context rather than treating individual money decisions in isolation — a loan, a credit card, or a savings account is examined alongside its fees, risks, and long-term trade-offs.') }}
            </p>
            <p class="mt-4 leading-relaxed text-body">
                {{ __('Articles focus on explaining concepts, identifying important considerations, and helping readers understand how different choices can affect households, professionals, and small businesses.') }}
            </p>
        </div>

        <div class="space-y-4">
            @foreach ($principles as $i => $principle)
                <div class="flex gap-5 rounded-3xl border border-line bg-surface p-6">
                    <span class="font-display text-3xl font-semibold text-brand-500">0{{ $i + 1 }}</span>
                    <div>
                        <h3 class="font-display text-xl font-semibold text-ink">{{ $principle['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-muted">{{ $principle['text'] }}</p>
                    </div>
                </div>
            @endforeach
            <p class="rounded-3xl bg-zest-200 p-6 text-sm leading-relaxed text-brand-900">
                <span class="font-bold">{{ __('Please note:') }}</span> {{ __('our content is intended for educational and informational purposes and should not be considered personalized financial, investment, tax, legal, or professional advice.') }}
            </p>
        </div>
    </section>

    {{-- CTA --}}
    <section class="px-4 pb-20 sm:px-6 lg:px-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2.5rem] bg-brand-700 px-8 py-16 text-center sm:px-14">
            <div class="absolute -bottom-24 -left-16 size-80 rounded-full border-[40px] border-zest-400/20"></div>
            <h2 class="relative font-display text-4xl font-semibold text-balance text-white">{{ __('Thank you for learning with :site', ['site' => $siteName]) }}</h2>
            <p class="relative mx-auto mt-5 max-w-2xl leading-relaxed text-brand-100">
                {{ __('We will keep developing educational resources designed to make complex subjects easier to understand and evaluate.') }}
            </p>
            <a href="{{ route('articles') }}" wire:navigate class="btn-zest relative mt-9">
                {{ __('Explore our articles') }}
                <flux:icon name="arrow-right" variant="mini" class="size-4" />
            </a>
        </div>
    </section>
@endsection
