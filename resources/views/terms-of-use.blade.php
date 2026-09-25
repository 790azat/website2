@extends('layouts.site')

@php
    $siteName = config('app.name', 'Laravel');
    $title = 'Terms of Use';
    $description = "Read the {$siteName} terms of use for the rules and guidelines that govern use of our site.";

    $lastUpdated = \Carbon\Carbon::parse('2026-09-01');

    $sections = [
        [
            'heading' => 'Acceptance of Terms',
            'body' => "These Terms of Use (\"Terms\") govern your access to and use of {$siteName}, including our website, articles, and any account features we offer. By accessing or using {$siteName}, you agree to be bound by these Terms. If you do not agree, please do not use the site.",
        ],
        [
            'heading' => 'Educational Use Only',
            'body' => "{$siteName} publishes content for general informational and educational purposes only. Nothing on this site constitutes personalized financial, investment, tax, or legal advice, and it should not be relied upon as such. You should consult a qualified professional before making decisions based on information found here.",
        ],
        [
            'heading' => 'Use of the Site',
            'body' => 'When using our site, you agree to:',
            'list' => [
                'Use the site only for lawful purposes and in a manner consistent with these Terms.',
                'Not attempt to gain unauthorized access to any part of the site, other accounts, or related systems.',
                'Not interfere with or disrupt the site, its servers, or its networks.',
                'Not scrape, copy, or republish substantial portions of our content without permission.',
                'Provide accurate information if you create an account with us.',
            ],
        ],
        [
            'heading' => 'Accounts',
            'body' => "If {$siteName} offers account or dashboard features, you are responsible for maintaining the confidentiality of your login credentials and for all activity that occurs under your account. Please notify us promptly if you suspect any unauthorized use of your account.",
        ],
        [
            'heading' => 'Intellectual Property',
            'body' => "Unless otherwise noted, all articles, graphics, logos, and other content on {$siteName} are the property of {$siteName} or its licensors and are protected by copyright and other intellectual property laws. You may view and share our content for personal, non-commercial use, but you may not reproduce, distribute, or create derivative works from it without our prior written consent.",
        ],
        [
            'heading' => 'Third-Party Links',
            'body' => "Our articles may reference or link to third-party websites, tools, or sources for further reading. These links are provided for convenience only. We do not control, endorse, or take responsibility for the content, accuracy, or practices of any third-party site.",
        ],
        [
            'heading' => 'Disclaimer of Warranties',
            'body' => "{$siteName} is provided on an \"as is\" and \"as available\" basis. While we aim to keep our content accurate and up to date, we make no warranties, express or implied, regarding the completeness, reliability, or accuracy of any information on the site.",
        ],
        [
            'heading' => 'Limitation of Liability',
            'body' => "To the fullest extent permitted by law, {$siteName} and its team members are not liable for any indirect, incidental, or consequential damages arising from your use of, or inability to use, the site or any content published on it.",
        ],
        [
            'heading' => 'Changes to These Terms',
            'body' => "We may update these Terms from time to time to reflect changes in our practices or for legal or operational reasons. When we do, we will revise the date at the top of this page. Continued use of the site after changes are posted constitutes your acceptance of the updated Terms.",
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'heading' => 'Terms of Use',
        'docName' => 'these Terms of Use',
        'closingNote' => "{$siteName} publishes educational and informational content only and is not a substitute for personalized financial, investment, tax, or legal advice. These Terms describe the rules for using our site and are not themselves legal advice.",
    ])
@endsection
