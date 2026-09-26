<?php

use App\Support\SiteContent;

// Datasets are built before the application boots, so the data file is
// read directly rather than through SiteContent.
dataset('programs', fn () => collect((require dirname(__DIR__, 2).'/resources/data/articles.php')['programs'])
    ->mapWithKeys(fn (array $program) => [$program['slug'] => [$program]])
    ->all());

test('program page renders', function (array $program) {
    $this->get(route('program', $program['slug']))
        ->assertOk()
        ->assertSee($program['title'])
        ->assertSee($program['cta_url'])
        ->assertSee('href="'.route('article', $program['related_slug']).'"', false);
})->with('programs');

test('every program links to an existing article in an existing section', function () {
    foreach (SiteContent::programs() as $program) {
        expect(SiteContent::section($program['section']))->not->toBeNull()
            ->and(SiteContent::article($program['related_slug']))->not->toBeNull();
    }
});

test('the home page lists every program', function () {
    $response = $this->get(route('home'))->assertOk();

    foreach (SiteContent::programs() as $program) {
        $response->assertSee(route('program', $program['slug']));
    }
});

test('unknown program returns 404', function () {
    $this->get(route('program', 'no-such-program'))->assertNotFound();
});
