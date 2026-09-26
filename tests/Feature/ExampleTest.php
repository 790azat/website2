<?php

use App\Support\SiteContent;

test('returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('hero card rotates through article links', function () {
    $article = SiteContent::articles('personal-finance')->first();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('hero-rotator', false)
        ->assertSee(route('article', $article['slug']), false);
});
