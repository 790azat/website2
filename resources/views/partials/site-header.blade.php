{{--
    Shared site header used on every public page.
    Expects: $categories = [['id' => 'slug', 'title' => 'Label'], ...]
    The including page must set x-data="{ mobileOpen: false }" on <body>.
--}}
@php
    $siteName = $siteName ?? config('app.name', 'Laravel');
@endphp

<header class="sticky top-0 z-40 bg-zinc-900">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-6 lg:px-8">
        <a href="{{ route('home') }}" wire:navigate class="flex shrink-0 items-center">
            <img src="{{ asset('images/branding/logo-full.png') }}" alt="{{ $siteName }}" class="h-8 w-auto" />
        </a>

        <nav class="hidden items-center gap-7 text-sm font-medium whitespace-nowrap text-zinc-300 lg:flex">
            <a href="{{ route('home') }}" wire:navigate class="transition hover:text-white">Home</a>
            <a href="{{ route('articles') }}" wire:navigate class="transition hover:text-white">All Articles</a>
            @foreach ($categories as $category)
                <a href="{{ route('section', $category['id']) }}" wire:navigate class="transition hover:text-white">{{ $category['title'] }}</a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <form class="relative hidden sm:block" onsubmit="return false" role="search" aria-label="Site search">
                <input
                    type="search"
                    placeholder="Search"
                    class="w-40 rounded-full border border-zinc-700 bg-zinc-800 py-1.5 pr-3 pl-9 text-sm text-white placeholder-zinc-400 focus:w-56 focus:border-zinc-500 focus:ring-0 focus:outline-none transition-[width]"
                />
                <svg class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-zinc-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                    <circle cx="9" cy="9" r="6" />
                    <path d="m17 17-4.35-4.35" stroke-linecap="round" />
                </svg>
            </form>

            <button
                type="button"
                @click="mobileOpen = true"
                class="flex size-9 items-center justify-center rounded-md text-white lg:hidden"
                aria-label="Open menu"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </div>
</header>

{{-- Mobile slide-over menu --}}
<div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" style="display: none;">
    <div class="fixed inset-0 bg-zinc-950/50" @click="mobileOpen = false"></div>
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 flex w-full max-w-xs flex-col gap-6 bg-white px-6 py-6 dark:bg-zinc-950"
    >
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5 font-semibold text-zinc-900 dark:text-white">
                <img src="{{ asset('images/branding/logo-icon.png') }}" alt="" class="size-8 shrink-0" />
                <span>{{ $siteName }}</span>
            </a>
            <button type="button" @click="mobileOpen = false" class="flex size-9 items-center justify-center rounded-md text-zinc-500 dark:text-zinc-400" aria-label="Close menu">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <form class="relative" onsubmit="return false" role="search" aria-label="Site search">
            <input
                type="search"
                placeholder="Search"
                class="w-full rounded-full border border-zinc-200 bg-zinc-50 py-2 pr-3 pl-9 text-sm text-zinc-900 placeholder-zinc-400 focus:border-zinc-400 focus:ring-0 focus:outline-none dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
            />
            <svg class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-zinc-400" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                <circle cx="9" cy="9" r="6" />
                <path d="m17 17-4.35-4.35" stroke-linecap="round" />
            </svg>
        </form>

        <nav class="flex flex-col gap-1 text-base font-medium text-zinc-700 dark:text-zinc-300">
            <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 hover:bg-zinc-100 dark:hover:bg-zinc-900">Home</a>
            <a href="{{ route('articles') }}" wire:navigate @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 hover:bg-zinc-100 dark:hover:bg-zinc-900">All Articles</a>
            @foreach ($categories as $category)
                <a href="{{ route('section', $category['id']) }}" wire:navigate @click="mobileOpen = false" class="rounded-lg px-3 py-2.5 hover:bg-zinc-100 dark:hover:bg-zinc-900">{{ $category['title'] }}</a>
            @endforeach
        </nav>
    </div>
</div>
