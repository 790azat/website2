@extends('layouts.site')

@php
    $siteName = config('app.name', 'Laravel');
    $title = 'Privacy Policy';
    $description = "Read the {$siteName} privacy policy to learn how we collect, use, and protect your information.";

    $lastUpdated = \Carbon\Carbon::parse('2026-09-01');

    $sections = [
        [
            'heading' => 'Introduction',
            'body' => "Welcome to {$siteName}. This Privacy Policy explains how we collect, use, and protect information when you visit our website and use the account features we offer, such as our reader dashboard. By using {$siteName}, you agree to the practices described in this policy.",
        ],
        [
            'heading' => 'Information We Collect',
            'body' => "We collect only the information needed to operate {$siteName} and to provide the account features we offer:",
            'list' => [
                'Account information — if you create an account, we collect your name and email address, along with a securely hashed password.',
                'Usage data — we may collect general information about how you interact with our site, such as pages viewed and links clicked, to help us understand what content is useful.',
                'Technical data — like most websites, our servers automatically log standard technical details such as browser type, device type, and IP address for security and troubleshooting purposes.',
                'Communications — if you contact us directly, such as through our Contact page, we keep a record of that correspondence so we can respond to you.',
            ],
        ],
        [
            'heading' => 'How We Use Information',
            'body' => 'We use the information we collect to:',
            'list' => [
                'Provide, maintain, and secure your account and our website.',
                'Respond to questions, feedback, and correction requests you send us.',
                'Understand, in aggregate, how our articles and sections are used so we can improve them.',
                'Detect, investigate, and prevent fraudulent or unauthorized activity.',
                'Comply with applicable legal obligations.',
            ],
        ],
        [
            'heading' => 'Cookies',
            'body' => "{$siteName} may use a small number of essential cookies to keep you signed in and to remember basic site preferences. We do not use cookies to build advertising profiles, and we do not sell any information collected through cookies. You can configure your browser to refuse cookies, though some features of the site may not work as intended if you do.",
        ],
        [
            'heading' => 'Third-Party Links',
            'body' => "Our articles may reference or link to third-party websites, tools, or sources for further reading. We are not responsible for the privacy practices or content of those external sites, and we encourage you to review their own privacy policies before sharing any information with them.",
        ],
        [
            'heading' => 'Data Security',
            'body' => 'We take reasonable technical and organizational measures to protect the information we hold from loss, misuse, and unauthorized access. However, no method of transmission or storage over the internet is completely secure, and we cannot guarantee absolute security.',
        ],
        [
            'heading' => "Children's Privacy",
            'body' => "{$siteName} is intended for a general business and professional audience and is not directed at children. We do not knowingly collect personal information from children. If you believe a child has provided us with personal information, please contact us so we can remove it.",
        ],
        [
            'heading' => 'Changes to This Policy',
            'body' => "We may update this Privacy Policy from time to time to reflect changes in our practices or for legal or operational reasons. When we do, we will revise the date at the top of this page. We encourage you to review this policy periodically.",
        ],
    ];
@endphp

@section('content')
    @include('partials.legal-page', [
        'heading' => 'Privacy Policy',
        'docName' => 'this Privacy Policy or how we handle your information',
        'closingNote' => "{$siteName} publishes educational and informational content only and is not a substitute for personalized financial, investment, tax, or legal advice. This policy describes our data practices and is not itself legal advice.",
    ])
@endsection
