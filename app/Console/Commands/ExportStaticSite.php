<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Exports the public site as static HTML for preview hosting (e.g. Vercel).
 *
 * Starting from the homepage, every internal link is rendered through the
 * HTTP kernel and written to disk: pages become "<path>.html" (served with
 * clean URLs), and PHP-served scripts such as livewire.js / flux.js are saved
 * at their own paths. The contents of public/ are copied alongside, and a
 * 404.html is produced from the app's own not-found page.
 */
class ExportStaticSite extends Command
{
    protected $signature = 'site:export {--out=dist : Output directory, relative to the project root}';

    protected $description = 'Export the public site as static HTML for preview hosting';

    /**
     * Placeholder origin used while rendering; replaced with root-relative URLs.
     */
    protected const ORIGIN = 'http://static.export';

    /**
     * Account/app routes that only work with a running Laravel backend.
     *
     * @var list<string>
     */
    protected array $excludedPrefixes = [
        '/login', '/logout', '/register', '/forgot-password', '/reset-password',
        '/email', '/two-factor', '/user', '/dashboard', '/settings', '/livewire/update',
    ];

    /** @var array<string, true> */
    protected array $visited = [];

    public function handle(Kernel $kernel, Filesystem $files): int
    {
        if (is_file(public_path('hot'))) {
            $this->error('public/hot exists (Vite dev server). Run `npm run build` and delete public/hot first.');

            return self::FAILURE;
        }

        // Rendering must not depend on a database or persistent sessions.
        config([
            'session.driver' => 'array',
            'cache.default' => 'array',
            'app.debug' => false,
        ]);

        $out = base_path(trim((string) $this->option('out'), '/\\'));
        $files->deleteDirectory($out);
        $files->ensureDirectoryExists($out);

        $this->copyPublicAssets($files, $out);

        $queue = ['/'];
        $pages = 0;

        while ($queue !== []) {
            $path = array_shift($queue);

            if (isset($this->visited[$path])) {
                continue;
            }
            $this->visited[$path] = true;

            [$status, $body, $isHtml] = $this->render($kernel, $path);

            if ($status !== 200) {
                $this->warn("  skipped {$path} (HTTP {$status})");

                continue;
            }

            if ($isHtml) {
                $pages++;
                foreach ($this->internalLinks($body) as $link) {
                    if (! isset($this->visited[$link])) {
                        $queue[] = $link;
                    }
                }
            }

            $target = $out.$this->targetPath($path, $isHtml);
            $files->ensureDirectoryExists(dirname($target));
            $files->put($target, $body);
        }

        [, $notFound] = $this->render($kernel, '/__static-export-not-found__');
        $files->put($out.'/404.html', $notFound);

        // Plain static files: no framework detection or build step on Vercel.
        $files->put($out.'/vercel.json', json_encode([
            'framework' => null,
            'buildCommand' => '',
            'installCommand' => '',
            'outputDirectory' => '.',
            'cleanUrls' => true,
            'trailingSlash' => false,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

        $this->info("Exported {$pages} pages to {$out}");

        return self::SUCCESS;
    }

    /**
     * @return array{0: int, 1: string, 2: bool}
     */
    protected function render(Kernel $kernel, string $path): array
    {
        // Livewire remembers per process that its scripts were injected;
        // reset it so every exported page gets its own <script> tags.
        if (class_exists(Livewire::class)) {
            Livewire::flushState();
        }

        $request = Request::create(self::ORIGIN.$path, 'GET');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        if ($response instanceof BinaryFileResponse) {
            $body = (string) file_get_contents($response->getFile()->getPathname());
        } elseif ($response instanceof StreamedResponse) {
            ob_start();
            $response->sendContent();
            $body = (string) ob_get_clean();
        } else {
            $body = (string) $response->getContent();
        }

        $isHtml = str_contains((string) $response->headers->get('Content-Type'), 'text/html');

        // Make every URL root-relative so the export works on any domain.
        $body = str_replace(
            [self::ORIGIN.'/', '"'.self::ORIGIN.'"', str_replace('/', '\/', self::ORIGIN).'\/', self::ORIGIN],
            ['/', '"/"', '\/', '/'],
            $body,
        );

        return [$response->getStatusCode(), $body, $isHtml];
    }

    /**
     * Internal page and script URLs referenced from an HTML document.
     *
     * @return list<string>
     */
    protected function internalLinks(string $html): array
    {
        preg_match_all('/(?:href|src)="(\/[^"]*)"/', $html, $matches);

        $links = [];

        foreach ($matches[1] as $url) {
            $url = html_entity_decode($url);
            $path = Str::before(Str::before($url, '#'), '?');

            if ($path === '' || str_starts_with($path, '//')) {
                continue;
            }

            if (str_contains(Str::before($url, '#'), '?') && ! str_ends_with($path, '.js')) {
                $this->warn("  query-string link cannot be exported statically: {$url}");

                continue;
            }

            if (is_file(public_path(ltrim($path, '/'))) || Str::startsWith($path, $this->excludedPrefixes)) {
                continue;
            }

            $links[] = $path === '/' ? '/' : rtrim($path, '/');
        }

        return array_values(array_unique($links));
    }

    protected function targetPath(string $path, bool $isHtml): string
    {
        if (! $isHtml) {
            return $path;
        }

        return $path === '/' ? '/index.html' : $path.'.html';
    }

    protected function copyPublicAssets(Filesystem $files, string $out): void
    {
        foreach ($files->allFiles(public_path(), true) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            if (in_array($relative, ['index.php', '.htaccess', 'hot'], true) || str_starts_with($relative, 'storage/')) {
                continue;
            }

            $files->ensureDirectoryExists(dirname($out.'/'.$relative));
            $files->copy($file->getPathname(), $out.'/'.$relative);
        }
    }
}
