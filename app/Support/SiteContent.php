<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Read-only access to the site content stored in resources/data/articles.php.
 *
 * Centralizes the lookups every public page needs (sections, authors,
 * articles, programs) and resolves image paths only when the file actually
 * exists in public/images, so pages fall back to generated artwork instead
 * of broken images.
 *
 * @phpstan-type Section array{title: string, order?: int, icon?: string, description?: string}
 * @phpstan-type Content array{sections: array<string, Section>, authors: array<string, array<string, mixed>>, articles: list<array<string, mixed>>, programs?: list<array<string, mixed>>}
 * @phpstan-type Category array{id: string, title: string, description: string|null, icon: string, order: int, count: int}
 */
class SiteContent
{
    /** @var Content|null */
    protected static ?array $data = null;

    /**
     * @return Content
     */
    public static function data(): array
    {
        return static::$data ??= require resource_path('data/articles.php');
    }

    /**
     * Sections ordered for navigation, each with id, title, icon and article count.
     *
     * @return Collection<int, Category>
     */
    public static function categories(): Collection
    {
        $data = static::data();
        $counts = array_count_values(array_column($data['articles'], 'section'));

        return collect($data['sections'])
            ->map(fn (array $meta, string $key) => [
                'id' => $key,
                'title' => $meta['title'],
                'description' => $meta['description'] ?? null,
                'icon' => $meta['icon'] ?? 'book-open',
                'order' => $meta['order'] ?? 99,
                'count' => $counts[$key] ?? 0,
            ])
            ->sortBy('order')
            ->values();
    }

    /**
     * @return Category|null
     */
    public static function section(string $key): ?array
    {
        return static::categories()->firstWhere('id', $key);
    }

    /**
     * Author record with a resolved photo (null when the file is missing).
     *
     * @return array<string, mixed>
     */
    public static function author(?string $key): array
    {
        $author = static::data()['authors'][$key] ?? [
            'name' => config('app.name').' Editorial Team',
            'role' => 'Editorial Team',
        ];

        $author['key'] = $key;
        $author['photo'] = static::image('team/'.($author['photo'] ?? ''));
        $author['initials'] = collect(explode(' ', $author['name']))
            ->map(fn (string $part) => mb_substr($part, 0, 1))
            ->take(2)
            ->implode('');

        return $author;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function authors(): Collection
    {
        return collect(static::data()['authors'])
            ->map(fn (array $author, string $key) => static::author($key))
            ->values();
    }

    /**
     * Articles newest first, optionally limited to one section, with author,
     * section and image metadata attached.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function articles(?string $section = null): Collection
    {
        return collect(static::data()['articles'])
            ->when($section, fn (Collection $c) => $c->where('section', $section))
            ->sortByDesc('date')
            ->values()
            ->map(fn (array $article) => static::withMeta($article));
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function article(string $slug): ?array
    {
        $article = collect(static::data()['articles'])->firstWhere('slug', $slug);

        return $article ? static::withMeta($article) : null;
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function programs(): Collection
    {
        return collect(static::data()['programs'] ?? [])
            ->map(static::withProgramImage(...));
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function program(string $slug): ?array
    {
        return static::programs()->firstWhere('slug', $slug);
    }

    /**
     * Path relative to public/images when the file exists, otherwise null.
     */
    public static function image(?string $path): ?string
    {
        if (blank($path) || str_ends_with($path, '/')) {
            return null;
        }

        return is_file(public_path('images/'.$path)) ? $path : null;
    }

    public static function domain(): string
    {
        return config('app.domain');
    }

    /**
     * @param  array<string, mixed>  $program
     * @return array<string, mixed>
     */
    protected static function withProgramImage(array $program): array
    {
        $program['hero_image'] = static::image($program['hero_image'] ?? null);

        return $program;
    }

    /**
     * First real paragraph of an article body (skipping headings, bullets
     * and short metadata lines), trimmed for use on cards.
     */
    protected static function excerpt(string $body): string
    {
        $blocks = preg_split('/\n\s*\n/', str_replace("\r\n", "\n", trim($body))) ?: [];

        $paragraph = collect($blocks)
            ->map(fn (string $block) => trim(preg_replace('/\s+/', ' ', $block)))
            ->first(fn (string $block) => mb_strlen($block) >= 80 && ! preg_match('/^(#|\* |- |\d+\.\s)/', $block), '');

        $paragraph = preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', '$1', $paragraph);

        return Str::limit($paragraph, 170);
    }

    /**
     * @param  array<string, mixed>  $article
     * @return array<string, mixed>
     */
    protected static function withMeta(array $article): array
    {
        $section = static::section($article['section']);

        $article['image'] = static::image($article['image'] ?? null);
        $article['author_info'] = static::author($article['author'] ?? null);
        $article['section_title'] = $section['title'] ?? '';
        $article['section_icon'] = $section['icon'] ?? 'book-open';
        $article['excerpt'] = $article['excerpt'] ?? static::excerpt($article['body']);
        $article['reading_minutes'] = max(1, (int) ceil(str_word_count(strip_tags($article['body'])) / 220));

        return $article;
    }
}
