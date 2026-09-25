{{--
    Shared site header. Expects $categories (from layouts.site) and
    x-data="{ mobileOpen: false }" on <body>.
--}}
@php
    $navLink = fn (bool $active) => $active
        ? 'text-brand-700 dark:text-brand-300 after:scale-x-100'
        : 'text-body hover:text-ink after:scale-x-0 hover:after:scale-x-100';
@endphp

{{-- Announcement strip --}}
<div class="bg-brand-900 text-brand-100">
    <div class="mx-auto flex h-9 max-w-7xl items-center justify-between gap-4 px-6 text-xs lg:px-8">
        <p class="flex items-center gap-2 truncate">
            <span class="size-1.5 shrink-0 rounded-full bg-zest-400"></span>
            {{ __('Free, independent guides to banking, investing, loans & credit') }}
        </p>
        <div class="flex shrink-0 items-center gap-5">
            <nav class="hidden items-center gap-5 font-medium sm:flex">
                <a href="{{ route('team') }}" wire:navigate class="transition hover:text-white">{{ __('Our Team') }}</a>
                <a href="{{ route('contact') }}" wire:navigate class="transition hover:text-white">{{ __('Contact') }}</a>
            </nav>
            @include('partials.language-switcher')
        </div>
    </div>
</div>

<header class="sticky top-0 z-40 border-b border-line bg-paper/85 backdrop-blur-md">
    <div class="mx-auto flex h-18 max-w-7xl items-center justify-between gap-6 px-6 lg:px-8">
        <a href="{{ route('home') }}" wire:navigate class="shrink-0" aria-label="{{ __(':site home', ['site' => config('app.name')]) }}">
            @include('partials.logo')
        </a>

        <nav class="hidden items-center gap-7 text-sm font-semibold whitespace-nowrap xl:flex">
            <a href="{{ route('home') }}" wire:navigate class="relative py-2 transition after:absolute after:inset-x-0 after:-bottom-0.5 after:h-0.5 after:origin-left after:rounded-full after:bg-brand-500 after:transition {{ $navLink(request()->routeIs('home')) }}">{{ __('Home') }}</a>
            @foreach ($categories as $category)
                <a href="{{ route('section', $category['id']) }}" wire:navigate class="relative py-2 transition after:absolute after:inset-x-0 after:-bottom-0.5 after:h-0.5 after:origin-left after:rounded-full after:bg-brand-500 after:transition {{ $navLink(request()->route('section') === $category['id']) }}">{{ $category['title'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('articles') }}" wire:navigate class="hidden items-center gap-2 rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-brand-700 sm:inline-flex dark:bg-brand-500 dark:text-brand-950 dark:hover:bg-brand-400">
                <flux:icon name="book-open" variant="mini" class="size-4" />
                {{ __('All Articles') }}
            </a>

            <button
                type="button"
                @click="mobileOpen = true"
                class="flex size-10 items-center justify-center rounded-full border border-line bg-surface text-ink transition hover:border-brand-400 xl:hidden"
                aria-label="{{ __('Open menu') }}"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M4 7h16M4 12h16M4 17h10" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile slide-over menu --}}
<div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 xl:hidden" @keydown.escape.window="mobileOpen = false">
    <div x-show="mobileOpen" x-transition.opacity class="fixed inset-0 bg-brand-950/60 backdrop-blur-sm" @click="mobileOpen = false"></div>
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 flex w-full max-w-sm flex-col bg-paper"
    >
        <div class="flex items-center justify-between border-b border-line px-6 py-5">
            <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false">
                @include('partials.logo', ['size' => 'sm'])
            </a>
            <button type="button" @click="mobileOpen = false" class="flex size-10 items-center justify-center rounded-full border border-line text-muted" aria-label="{{ __('Close menu') }}">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-4 py-6">
            <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false" class="rounded-2xl px-4 py-3 font-semibold text-ink hover:bg-soft">{{ __('Home') }}</a>
            <a href="{{ route('articles') }}" wire:navigate @click="mobileOpen = false" class="rounded-2xl px-4 py-3 font-semibold text-ink hover:bg-soft">{{ __('All Articles') }}</a>

            <p class="mt-5 mb-2 px-4 text-xs font-bold tracking-[0.16em] text-muted uppercase">{{ __('Topics') }}</p>
            @foreach ($categories as $category)
                <a href="{{ route('section', $category['id']) }}" wire:navigate @click="mobileOpen = false" class="flex items-center gap-3 rounded-2xl px-4 py-3 font-medium text-body hover:bg-soft">
                    <span class="flex size-8 items-center justify-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/60 dark:text-brand-200">
                        <flux:icon name="{{ $category['icon'] }}" variant="mini" class="size-4" />
                    </span>
                    {{ $category['title'] }}
                </a>
            @endforeach

            <p class="mt-5 mb-2 px-4 text-xs font-bold tracking-[0.16em] text-muted uppercase">{{ __('About') }}</p>
            <a href="{{ route('team') }}" wire:navigate @click="mobileOpen = false" class="rounded-2xl px-4 py-3 font-medium text-body hover:bg-soft">{{ __('Our Team') }}</a>
            <a href="{{ route('contact') }}" wire:navigate @click="mobileOpen = false" class="rounded-2xl px-4 py-3 font-medium text-body hover:bg-soft">{{ __('Contact') }}</a>
        </nav>
    </div>
</div>
