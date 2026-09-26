{{--
    Brand logo: the full Edufinance.site mark + wordmark image.
    Options: $invert (bool) for dark backgrounds, $size ('sm' | 'md').
    Dark mode swaps to the light-wordmark variant automatically.
--}}
@php
    $invert = $invert ?? false;
    $height = ($size ?? 'md') === 'sm' ? 'h-8' : 'h-10';
    $alt = config('app.name', 'Laravel');
@endphp
@if ($invert)
    <img src="/images/brand/logo-light.webp" alt="{{ $alt }}" width="760" height="128" class="{{ $height }} w-auto shrink-0">
@else
    <img src="/images/brand/logo.webp" alt="{{ $alt }}" width="760" height="128" class="{{ $height }} w-auto shrink-0 dark:hidden">
    <img src="/images/brand/logo-light.webp" alt="{{ $alt }}" width="760" height="128" class="{{ $height }} hidden w-auto shrink-0 dark:block">
@endif
