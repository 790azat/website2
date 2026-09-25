{{--
    Shared shell for every public page.
    Child views extend 'layouts.site', define $title (page name only, or null
    on the homepage) and $description in a php block, and fill the 'content'
    section.
--}}
@php
    $siteName = config('app.name', 'Laravel');
    $categories = \App\Support\SiteContent::categories();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? null])
        @if (filled($description ?? null))
            <meta name="description" content="{{ $description }}" />
        @endif
    </head>
    <body
        x-data="{ mobileOpen: false }"
        class="min-h-screen bg-paper font-sans text-ink antialiased selection:bg-zest-300 selection:text-brand-950"
    >
        @include('partials.site-header')

        <main>
            @yield('content')
        </main>

        @include('partials.site-footer')

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
