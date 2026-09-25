<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('our-team', 'team')->name('team');
Route::view('contact', 'contact')->name('contact');
Route::view('privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('terms-of-use', 'terms-of-use')->name('terms-of-use');

Route::view('articles', 'all-articles')->name('articles');
Route::view('c/{section}', 'section')->name('section');
Route::view('p/{slug}', 'article')->name('article');
Route::view('programs/{slug}', 'program')->name('program');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
