{{--
    Shared site footer used on every public page.
--}}
@php
    $siteName = $siteName ?? config('app.name', 'Laravel');
@endphp

<footer class="bg-zinc-900">
    <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
        <div class="flex flex-col items-center gap-6 border-b border-zinc-800 pb-8 sm:flex-row sm:justify-between">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center">
                <img src="{{ asset('images/branding/logo-full.png') }}" alt="{{ $siteName }}" class="h-7 w-auto" />
            </a>

            <nav class="flex flex-wrap items-center justify-center gap-x-2 gap-y-2 text-sm text-zinc-400">
                <a href="{{ route('home') }}" wire:navigate class="hover:text-white">Home</a>
                <span class="text-zinc-700">/</span>
                <a href="{{ route('team') }}" wire:navigate class="hover:text-white">Our Editorial Team</a>
                <span class="text-zinc-700">/</span>
                <a href="{{ route('contact') }}" wire:navigate class="hover:text-white">Contact</a>
                <span class="text-zinc-700">/</span>
                <a href="{{ route('privacy-policy') }}" wire:navigate class="hover:text-white">Privacy Policy</a>
                <span class="text-zinc-700">/</span>
                <a href="{{ route('terms-of-use') }}" wire:navigate class="hover:text-white">Terms of Use</a>
            </nav>
        </div>

        <p class="mt-6 text-xs leading-relaxed text-zinc-500">
            Information shared by {{ $siteName }} is intended for informational and educational purposes only and should not be interpreted as investment, tax, or legal advice. Financial decisions carry risk, and past performance does not guarantee future results. Always seek advice from a certified professional before making financial decisions.
        </p>

        <p class="mt-4 text-xs text-zinc-600">
            &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
        </p>
    </div>
</footer>
