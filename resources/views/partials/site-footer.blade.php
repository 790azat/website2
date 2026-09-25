{{--
    Shared site footer. Expects $categories and $siteName (from layouts.site).
--}}
<footer class="relative overflow-hidden bg-brand-950 text-brand-100">
    <div class="pointer-events-none absolute -top-32 -right-24 size-96 rounded-full bg-brand-700/30 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-6 pt-16 pb-10 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <a href="{{ route('home') }}" wire:navigate aria-label="{{ $siteName }} home">
                    @include('partials.logo', ['invert' => true])
                </a>
                <p class="mt-5 max-w-sm leading-relaxed text-brand-200/80">
                    Clear, research-driven lessons on money, business, and technology &mdash; written to help you make confident decisions.
                </p>
                <a href="{{ route('articles') }}" wire:navigate class="btn-zest mt-7">
                    Start learning
                    <flux:icon name="arrow-right" variant="mini" class="size-4" />
                </a>
            </div>

            <div class="grid gap-10 sm:grid-cols-2 lg:col-span-7 lg:grid-cols-3">
                <div>
                    <p class="text-xs font-bold tracking-[0.16em] text-zest-400 uppercase">Topics</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach ($categories as $category)
                            <li><a href="{{ route('section', $category['id']) }}" wire:navigate class="text-brand-100/80 transition hover:text-white">{{ $category['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-[0.16em] text-zest-400 uppercase">{{ $siteName }}</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('articles') }}" wire:navigate class="text-brand-100/80 transition hover:text-white">All Articles</a></li>
                        <li><a href="{{ route('team') }}" wire:navigate class="text-brand-100/80 transition hover:text-white">Our Editorial Team</a></li>
                        <li><a href="{{ route('contact') }}" wire:navigate class="text-brand-100/80 transition hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-[0.16em] text-zest-400 uppercase">Legal</p>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('privacy-policy') }}" wire:navigate class="text-brand-100/80 transition hover:text-white">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-of-use') }}" wire:navigate class="text-brand-100/80 transition hover:text-white">Terms of Use</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-14 rounded-2xl border border-brand-800 bg-brand-900/40 p-5 text-xs leading-relaxed text-brand-200/70">
            <span class="font-semibold text-brand-100">Educational content only.</span>
            Information shared by {{ $siteName }} is intended for informational and educational purposes and should not be interpreted as investment, tax, or legal advice. Financial decisions carry risk, and past performance does not guarantee future results. Always seek advice from a certified professional before making financial decisions.
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-3 text-xs text-brand-300/70 sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            <p>{{ \App\Support\SiteContent::domain() }}</p>
        </div>
    </div>

    <p aria-hidden="true" class="pointer-events-none -mb-6 text-center font-display text-[18vw] leading-none font-semibold tracking-tighter text-brand-900/70 select-none lg:text-[13rem]">
        {{ $siteName }}
    </p>
</footer>
