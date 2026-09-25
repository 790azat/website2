{{--
    Renders a raw article body (plain text with blank-line-separated blocks,
    "## " / "### " headings, "* " bullet lists, and a References section)
    into styled HTML.

    Expects: $body (string) — the raw article text from resources/data/articles.php.
--}}
@php
    if (! function_exists('render_article_body')) {
        function render_article_body(string $raw): string
        {
            $e = fn (string $s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

            // Renders "[label](https://url)" markdown-style links found inside
            // an otherwise plain-text line as real <a> tags; any text without
            // that syntax is escaped and returned unchanged (safe no-op for
            // article bodies that don't use link syntax).
            $linkify = function (string $text) use ($e): string {
                if (! preg_match_all('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', $text, $matches, PREG_OFFSET_CAPTURE)) {
                    return $e($text);
                }
                $html = '';
                $pos = 0;
                foreach ($matches[0] as $i => $full) {
                    $start = $full[1];
                    if ($start > $pos) {
                        $html .= $e(substr($text, $pos, $start - $pos));
                    }
                    $label = $matches[1][$i][0];
                    $url = $matches[2][$i][0];
                    $html .= '<a href="'.$e($url).'" target="_blank" rel="noopener noreferrer nofollow" class="underline decoration-zinc-300 hover:decoration-zinc-500 dark:decoration-zinc-600 dark:hover:decoration-zinc-400">'.$e($label).'</a>';
                    $pos = $start + strlen($full[0]);
                }
                if ($pos < strlen($text)) {
                    $html .= $e(substr($text, $pos));
                }

                return $html;
            };

            $raw = str_replace("\r\n", "\n", $raw);
            $blocks = preg_split('/\n{2,}/', trim($raw));
            $html = '';
            $lastHeadingWasReferences = false;

            foreach ($blocks as $block) {
                $block = trim($block);
                if ($block === '') {
                    continue;
                }

                // Single-line "### Heading"
                if (! str_contains($block, "\n") && str_starts_with($block, '### ')) {
                    $html .= '<h3 class="mt-8 mb-3 text-lg font-semibold text-zinc-900 dark:text-white">'.$e(trim(substr($block, 4))).'</h3>'."\n";
                    $lastHeadingWasReferences = false;
                    continue;
                }

                // Single-line "## Heading"
                if (! str_contains($block, "\n") && str_starts_with($block, '## ')) {
                    $text = trim(substr($block, 3));
                    $html .= '<h2 class="mt-10 mb-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">'.$e($text).'</h2>'."\n";
                    $lastHeadingWasReferences = (strcasecmp($text, 'References') === 0);
                    continue;
                }

                // Some articles use a bare "References" line without "## "
                if (! str_contains($block, "\n") && strcasecmp(trim($block), 'References') === 0) {
                    $html .= '<h2 class="mt-10 mb-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-white">'.$e(trim($block)).'</h2>'."\n";
                    $lastHeadingWasReferences = true;
                    continue;
                }

                $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), fn ($l) => $l !== ''));

                // A line is a list item if it starts with "* " (bullet) or
                // "N. " (numbered step, e.g. "1. Define the objective: ...").
                $bulletText = function (string $line): ?string {
                    if (str_starts_with($line, '* ') || str_starts_with($line, '- ')) {
                        return trim(substr($line, 2));
                    }
                    if (preg_match('/^\d+\.\s+(.*)$/', $line, $m)) {
                        return trim($m[1]);
                    }
                    return null;
                };
                $isNumbered = fn (string $line): bool => (bool) preg_match('/^\d+\.\s+/', $line);

                if ($lastHeadingWasReferences) {
                    $items = [];
                    foreach ($lines as $line) {
                        $bt = $bulletText($line);
                        if ($bt !== null) {
                            $items[] = $bt;
                        } elseif (! str_starts_with($line, 'http')) {
                            // A non-bulleted, non-URL line inside the references
                            // block (citation text without a leading "* ").
                            $items[] = $line;
                        }
                    }
                    if ($items) {
                        $html .= '<ul class="mt-2 space-y-1.5 text-sm text-zinc-500 dark:text-zinc-400">';
                        foreach ($items as $item) {
                            $html .= '<li class="pl-4 relative before:absolute before:left-0 before:content-[\'—\']">'.$linkify($item).'</li>';
                        }
                        $html .= '</ul>'."\n";
                    }
                    $lastHeadingWasReferences = false;
                    continue;
                }

                $segments = [];
                $currentList = null;
                $currentListType = 'ul';
                $currentPara = null;
                foreach ($lines as $line) {
                    $bt = $bulletText($line);
                    if ($bt !== null) {
                        if ($currentPara !== null) {
                            $segments[] = ['p', $currentPara];
                            $currentPara = null;
                        }
                        if ($currentList === null) {
                            $currentList = [];
                            $currentListType = $isNumbered($line) ? 'ol' : 'ul';
                        }
                        $currentList[] = $bt;
                    } else {
                        if ($currentList !== null) {
                            $segments[] = [$currentListType, $currentList];
                            $currentList = null;
                        }
                        $currentPara = $currentPara === null ? $line : $currentPara.' '.$line;
                    }
                }
                if ($currentList !== null) {
                    $segments[] = [$currentListType, $currentList];
                }
                if ($currentPara !== null) {
                    $segments[] = ['p', $currentPara];
                }

                foreach ($segments as [$type, $content]) {
                    if ($type === 'p') {
                        $html .= '<p class="mt-5 leading-relaxed text-zinc-700 dark:text-zinc-300">'.$e($content).'</p>'."\n";
                    } elseif ($type === 'ol') {
                        $html .= '<ol class="mt-5 space-y-3 text-zinc-700 dark:text-zinc-300">';
                        foreach ($content as $i => $item) {
                            $n = $i + 1;
                            if (preg_match('/^([^:]{1,80}):\s(.+)$/', $item, $m)) {
                                $html .= '<li class="flex gap-3"><span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-[11px] font-medium text-white dark:bg-white dark:text-zinc-900">'.$n.'</span><span class="leading-relaxed"><span class="font-medium text-zinc-900 dark:text-white">'.$e($m[1]).':</span> '.$e($m[2]).'</span></li>';
                            } else {
                                $html .= '<li class="flex gap-3"><span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-[11px] font-medium text-white dark:bg-white dark:text-zinc-900">'.$n.'</span><span class="leading-relaxed">'.$e($item).'</span></li>';
                            }
                        }
                        $html .= '</ol>'."\n";
                    } else {
                        $html .= '<ul class="mt-5 space-y-2 text-zinc-700 dark:text-zinc-300">';
                        foreach ($content as $item) {
                            // Bold the part before a colon, e.g. "Term: description".
                            if (preg_match('/^([^:]{1,80}):\s(.+)$/', $item, $m)) {
                                $html .= '<li class="flex gap-2"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-zinc-400 dark:bg-zinc-600"></span><span class="leading-relaxed"><span class="font-medium text-zinc-900 dark:text-white">'.$e($m[1]).':</span> '.$e($m[2]).'</span></li>';
                            } else {
                                $html .= '<li class="flex gap-2"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-zinc-400 dark:bg-zinc-600"></span><span class="leading-relaxed">'.$e($item).'</span></li>';
                            }
                        }
                        $html .= '</ul>'."\n";
                    }
                }
            }

            return $html;
        }
    }
@endphp

<div class="article-body">
    {!! render_article_body($body) !!}
</div>
