{{--
    Brand logo: open-book mark with a lime "coin", plus a two-tone wordmark.
    Options: $invert (bool) for dark backgrounds, $size ('sm' | 'md').
--}}
@php
    $invert = $invert ?? false;
    $words = preg_split('/(?=[A-Z])/', config('app.name', 'Laravel'), -1, PREG_SPLIT_NO_EMPTY);
    $first = array_shift($words);
    $rest = implode('', $words);
    $markSize = ($size ?? 'md') === 'sm' ? 'size-8' : 'size-9';
@endphp
<span class="inline-flex items-center gap-2.5">
    <svg viewBox="0 0 40 40" class="{{ $markSize }} shrink-0" aria-hidden="true">
        <rect width="40" height="40" rx="12" class="{{ $invert ? 'fill-brand-500' : 'fill-brand-600' }}" />
        <path d="M8.5 27.5c4-1.7 7.8-1.5 11.5.9 3.7-2.4 7.5-2.6 11.5-.9V14.2c-4-1.7-7.8-1.5-11.5.9-3.7-2.4-7.5-2.6-11.5-.9Z" fill="none" stroke="#fff" stroke-width="2.2" stroke-linejoin="round" />
        <path d="M20 15.1v13.3" stroke="#fff" stroke-width="2.2" stroke-linecap="round" />
        <circle cx="29.5" cy="10.5" r="4.5" class="fill-zest-400" />
    </svg>
    <span class="font-display text-[1.35rem] leading-none font-semibold tracking-tight {{ $invert ? 'text-white' : 'text-ink' }}">{{ $first }}<span class="{{ $invert ? 'text-zest-400' : 'text-brand-600 dark:text-brand-400' }}">{{ $rest }}</span></span>
</span>
