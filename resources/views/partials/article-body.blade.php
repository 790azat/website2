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
                    $html .= '<a href="'.$e($url).'" target="_blank" rel="noopener noreferrer nofollow" class="link-underline font-medium text-brand-700 dark:text-brand-300">'.$e($label).'</a>';
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
                    $html .= '<h3 class="mt-10 mb-3 font-display text-2xl font-semibold text-ink">'.$e(trim(substr($block, 4))).'</h3>'."\n";
                    $lastHeadingWasReferences = false;
                    continue;
                }

                // Single-line "## Heading"
                if (! str_contains($block, "\n") && str_starts_with($block, '## ')) {
                    $text = trim(substr($block, 3));
                    $html .= '<h2 class="mt-14 mb-5 font-display text-3xl leading-tight font-semibold tracking-tight text-ink">'.$e($text).'</h2>'."\n";
                    $lastHeadingWasReferences = (strcasecmp($text, 'References') === 0);
                    continue;
                }

                // Some articles use a bare "References" line without "## "
                if (! str_contains($block, "\n") && strcasecmp(trim($block), 'References') === 0) {
                    $html .= '<h2 class="mt-14 mb-5 font-display text-3xl leading-tight font-semibold tracking-tight text-ink">'.$e(trim($block)).'</h2>'."\n";
                    $lastHeadingWasReferences = true;
                    continue;
                }

                // Bare subheading: a short single line with no sentence
                // punctuation at the end (e.g. "How AI Is Changing Operations").
                if (! str_contains($block, "\n")
                    && mb_strlen($block) <= 90
                    && preg_match('/^[A-Z0-9]/', $block)
                    && ! preg_match('/[.,;:!?)"\']$/', $block)
                    && ! preg_match('/^(\* |- |\d+\.\s)/', $block)) {
                    $html .= '<h2 class="mt-14 mb-5 font-display text-3xl leading-tight font-semibold tracking-tight text-ink">'.$e($block).'</h2>'."\n";
                    $lastHeadingWasReferences = false;
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
                        $html .= '<ul class="mt-2 space-y-2 rounded-2xl bg-soft p-5 text-sm text-muted">';
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
                        $html .= '<p class="mt-6 text-[1.075rem] leading-[1.85] text-body">'.$e($content).'</p>'."\n";
                    } elseif ($type === 'ol') {
                        $html .= '<ol class="mt-6 space-y-4 text-[1.05rem] text-body">';
                        foreach ($content as $i => $item) {
                            $n = $i + 1;
                            if (preg_match('/^([^:]{1,80}):\s(.+)$/', $item, $m)) {
                                $html .= '<li class="flex gap-3"><span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 font-display text-sm font-semibold text-white dark:bg-brand-500 dark:text-brand-950">'.$n.'</span><span class="leading-relaxed"><span class="font-bold text-ink">'.$e($m[1]).':</span> '.$e($m[2]).'</span></li>';
                            } else {
                                $html .= '<li class="flex gap-3"><span class="mt-0.5 flex size-7 shrink-0 items-center justify-center rounded-full bg-brand-600 font-display text-sm font-semibold text-white dark:bg-brand-500 dark:text-brand-950">'.$n.'</span><span class="leading-relaxed">'.$e($item).'</span></li>';
                            }
                        }
                        $html .= '</ol>'."\n";
                    } else {
                        $html .= '<ul class="mt-6 space-y-3 text-[1.05rem] text-body">';
                        foreach ($content as $item) {
                            // Bold the part before a colon, e.g. "Term: description".
                            if (preg_match('/^([^:]{1,80}):\s(.+)$/', $item, $m)) {
                                $html .= '<li class="flex gap-3.5"><span class="mt-2.5 size-2 shrink-0 rounded-full bg-brand-500 ring-4 ring-brand-100 dark:ring-brand-900"></span><span class="leading-relaxed"><span class="font-bold text-ink">'.$e($m[1]).':</span> '.$e($m[2]).'</span></li>';
                            } else {
                                $html .= '<li class="flex gap-3.5"><span class="mt-2.5 size-2 shrink-0 rounded-full bg-brand-500 ring-4 ring-brand-100 dark:ring-brand-900"></span><span class="leading-relaxed">'.$e($item).'</span></li>';
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
