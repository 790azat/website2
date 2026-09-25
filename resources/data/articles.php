<?php

/**
 * Site content data: sections, authors, and articles.
 *
 * This file is the single source of truth for all published articles.
 * There is no database migration available for this project, so all
 * content is stored here as a plain PHP array and rendered through
 * shared Blade templates (see resources/views/article.blade.php and
 * resources/views/section.blade.php).
 */

return [

    'sections' => [
        'data-intelligence' => ['title' => 'Data Intelligence', 'order' => 1],
        'business-strategy' => ['title' => 'Business Strategy', 'order' => 2],
        'digital-horizons' => ['title' => 'Digital Horizons', 'order' => 3],
        'people-impact' => ['title' => 'People & Impact', 'order' => 4],
    ],

    'authors' => [
        'emily-carter' => [
            'name' => 'Emily Carter',
            'role' => 'Investment Consultant',
            'photo' => 'emily-carter.jpg',
        ],
        'james-mitchell' => [
            'name' => 'James Mitchell',
            'role' => 'Financial Specialist',
            'photo' => 'james-mitchell.jpg',
        ],
        'michael-anderson' => [
            'name' => 'Michael Anderson',
            'role' => 'Business and Data Analyst',
            'photo' => 'michael-anderson.jpg',
        ],
        'daniel-brooks' => [
            'name' => 'Daniel Brooks',
            'role' => 'Financial Specialist',
            'photo' => 'daniel-brooks.jpg',
        ],
    ],

    'articles' => [
    ],

    'programs' => [
    ],

];
