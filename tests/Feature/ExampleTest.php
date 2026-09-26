<?php

use App\Support\SiteContent;

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('hero card rotates through articles with cover images', function () {
    $article = SiteContent::articles()->first(fn ($article) => $article['image']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('hero-rotator', false)
        ->assertSee(route('article', $article['slug']), false);
});

test('homepage has an article search', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('id="hero-search"', false)
        ->assertSee('x-ref="result0"', false);
});

test('disclaimer page is linked from the footer', function () {
    $this->get(route('disclaimer'))
        ->assertOk()
        ->assertSee('Investment Risk')
        ->assertSee('Edufinance.site makes no representations');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('disclaimer'), false)
        ->assertSee('Past performance is not indicative of future results.');
});

test('homepage hero lists the three latest guides', function () {
    $response = $this->get(route('home'))->assertOk()->assertSee('Latest Guides');

    foreach (SiteContent::articles()->take(3) as $article) {
        $response->assertSee(route('article', $article['slug']), false);
    }
});
