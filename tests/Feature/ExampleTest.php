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
        ->assertSee('id="hero-search"', false);
});
