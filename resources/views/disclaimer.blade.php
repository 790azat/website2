@extends('layouts.site')

@php
    $siteName = config('app.name', 'Laravel');
    $siteDomain = ucfirst(\App\Support\SiteContent::domain());
    $title = __('Disclaimer');
    $description = __('Read the :site disclaimer: our content is educational only and is not professional financial, legal, or tax advice.', ['site' => $siteName]);

    $lastUpdated = \Carbon\Carbon::parse('2026-09-26');

    $sections = [
        [
            'heading' => __('Educational Purposes Only'),
            'body' => __('The content provided on :domain is for informational and educational purposes only and should not be construed as professional financial, legal, or tax advice. The creators and editors of this site are not licensed financial advisors.', ['domain' => $siteDomain]),
        ],
        [
            'heading' => __('Investment Risk'),
            'body' => __('Investing involves risk, including the potential loss of principal. Past performance is not indicative of future results. Before making any financial decisions or implementing any investment strategies, you should conduct your own independent research and consult with a qualified, licensed professional who understands your specific financial situation.'),
        ],
        [
            'heading' => __('No Warranties or Liability'),
            'body' => __(':domain makes no representations or warranties as to the accuracy, completeness, or suitability of the information contained herein, and assumes no liability for any financial losses or damages arising from the use of this content.', ['domain' => $siteDomain]),
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'heading' => __('Disclaimer'),
        'docName' => __('this Disclaimer'),
        'closingNote' => __('This Disclaimer applies to all content published on :site, including articles, guides, and program pages.', ['site' => $siteName]),
    ])
@endsection
