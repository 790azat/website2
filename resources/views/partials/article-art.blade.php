{{--
    Cover area for an article card: the article image when it exists,
    otherwise generated brand artwork with the section icon.
    Expects $article; optional $iconClass.
--}}
@if ($article['image'])
    <img
        src="{{ asset('images/'.$article['image']) }}"
        alt="{{ $article['title'] }}"
        loading="lazy"
        decoding="async"
        class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105"
    />
@else
    <div class="absolute inset-0 bg-brand-700 bg-[radial-gradient(circle_at_85%_15%,var(--color-brand-500),transparent_55%),radial-gradient(circle_at_10%_100%,var(--color-brand-900),transparent_60%)]">
        <div class="absolute inset-0 bg-[radial-gradient(var(--color-brand-500)_1px,transparent_1px)] [background-size:18px_18px] opacity-40"></div>
        <div class="absolute -right-6 -bottom-8 size-32 rounded-full border-[14px] border-zest-400/25"></div>
        <div class="absolute top-5 left-5 size-3 rounded-full bg-zest-400"></div>
    </div>
    <flux:icon name="{{ $article['section_icon'] }}" class="relative {{ $iconClass ?? 'size-12' }} text-white/90 transition duration-500 group-hover:scale-110" />
@endif
