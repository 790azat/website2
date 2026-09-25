{{--
    Numbered pagination. Expects $page, $lastPage and $pageLink (fn(int): string).
--}}
@if ($lastPage > 1)
    @php
        $pageWindow = collect(range(max(1, $page - 2), min($lastPage, $page + 2)))
            ->when($page - 2 > 1, fn ($c) => $c->prepend('…')->prepend(1))
            ->when($page + 2 < $lastPage, fn ($c) => $c->push('…')->push($lastPage))
            ->values();
        $base = 'flex h-11 min-w-11 items-center justify-center rounded-full px-4 text-sm font-semibold transition';
        $idle = 'border border-line bg-surface text-body hover:border-brand-400 hover:text-brand-700 dark:hover:text-brand-300';
    @endphp
    <nav class="mt-14 flex flex-wrap items-center justify-center gap-2" aria-label="Pagination">
        @if ($page > 1)
            <a href="{{ $pageLink($page - 1) }}" wire:navigate class="{{ $base }} {{ $idle }} gap-1.5">
                <flux:icon name="arrow-left" variant="mini" class="size-4" /> Previous
            </a>
        @endif

        @foreach ($pageWindow as $p)
            @if ($p === '…')
                <span class="px-1 text-sm text-muted">…</span>
            @elseif ($p === $page)
                <span aria-current="page" class="{{ $base }} bg-brand-600 text-white dark:bg-brand-500 dark:text-brand-950">{{ $p }}</span>
            @else
                <a href="{{ $pageLink($p) }}" wire:navigate class="{{ $base }} {{ $idle }}">{{ $p }}</a>
            @endif
        @endforeach

        @if ($page < $lastPage)
            <a href="{{ $pageLink($page + 1) }}" wire:navigate class="{{ $base }} {{ $idle }} gap-1.5">
                Next <flux:icon name="arrow-right" variant="mini" class="size-4" />
            </a>
        @endif
    </nav>
@endif
