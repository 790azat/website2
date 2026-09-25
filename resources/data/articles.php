<?php

/**
 * Site content data: sections, authors, articles, and programs.
 *
 * This file is the single source of truth for all published content.
 * There is no database, so everything is stored here as a plain PHP array
 * and read through App\Support\SiteContent by the Blade templates.
 *
 * Sections: 'title', 'order', optional 'icon' (Heroicon name) and
 *   'description' (shown on the homepage topic cards and section header).
 * Authors: 'name', 'role', 'photo' (file in public/images/team/), optional
 *   'bio' (shown on the Our Team page).
 * Articles: 'slug', 'title', 'section', 'author', 'date', 'image' (path in
 *   public/images/), 'body', optional 'excerpt'.
 * Missing image files fall back to generated artwork automatically.
 */

return [

    'sections' => [
        'data-intelligence' => [
            'title' => 'Data Intelligence',
            'order' => 1,
            'icon' => 'chart-bar',
            'description' => 'Understand the numbers behind everyday money and business decisions.',
        ],
        'business-strategy' => [
            'title' => 'Business Strategy',
            'order' => 2,
            'icon' => 'arrow-trending-up',
            'description' => 'Practical frameworks for growth, planning, and smarter choices.',
        ],
        'digital-horizons' => [
            'title' => 'Digital Horizons',
            'order' => 3,
            'icon' => 'cpu-chip',
            'description' => 'The tools and technology reshaping how we earn, save, and work.',
        ],
        'people-impact' => [
            'title' => 'People & Impact',
            'order' => 4,
            'icon' => 'academic-cap',
            'description' => 'Careers, learning, and the skills that compound over a lifetime.',
        ],
    ],

    'authors' => [
        'emily-carter' => [
            'name' => 'Emily Carter',
            'role' => 'Investment Consultant',
            'photo' => 'emily-carter.jpg',
            'bio' => 'Emily specializes in emerging markets and risk management, and writes practical guides on managing uncertainty and protecting long-term wealth.',
        ],
        'james-mitchell' => [
            'name' => 'James Mitchell',
            'role' => 'Financial Specialist',
            'photo' => 'james-mitchell.jpg',
            'bio' => 'James focuses on navigating volatile markets and explains how diversified, resilient strategies are built step by step.',
        ],
        'michael-anderson' => [
            'name' => 'Michael Anderson',
            'role' => 'Business and Data Analyst',
            'photo' => 'michael-anderson.jpg',
            'bio' => 'Michael helps readers turn complex information into practical insight, covering data analysis, performance measurement, and data-driven decisions.',
        ],
        'daniel-brooks' => [
            'name' => 'Daniel Brooks',
            'role' => 'Financial Specialist',
            'photo' => 'daniel-brooks.jpg',
            'bio' => 'Daniel writes about market volatility and portfolio diversification, balancing growth opportunities with prudent risk management.',
        ],
    ],

    'articles' => [
    ],

    'programs' => [
    ],

];
