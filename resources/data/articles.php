<?php

/**
 * Site content data: sections, authors, and programs.
 *
 * There is no database; this file and the Markdown files next to it are the
 * single source of truth, read through App\Support\SiteContent.
 *
 * Articles live in resources/data/articles/{slug}.md: a front-matter block
 * (title, section, author, date, optional image/excerpt) followed by the
 * Markdown body. Images are picked up automatically from
 * public/images/articles/{slug}.{webp,jpg,jpeg,png} (or the front-matter
 * "image" path); without one, generated artwork is shown.
 *
 * Sections: 'title', 'order', optional 'icon' (Heroicon name) and
 *   'description' (shown on topic cards and the section header).
 * Authors: 'name', 'role', 'bio', optional 'photo' (file in
 *   public/images/team/). Without 'photo', public/images/team/{key}.{webp,jpg,
 *   jpeg,png} is used when present, otherwise the author's initials.
 */

return [

    'sections' => [
        'personal-finance' => [
            'title' => 'Personal Finance',
            'order' => 1,
            'icon' => 'wallet',
            'description' => 'Banking, budgeting, insurance, and taxes — the everyday money decisions that add up.',
        ],
        'wealth-management' => [
            'title' => 'Wealth Management',
            'order' => 2,
            'icon' => 'chart-pie',
            'description' => 'Investing, retirement, and estate planning for building wealth that lasts.',
        ],
        'loans-financing' => [
            'title' => 'Loans & Financing',
            'order' => 3,
            'icon' => 'building-library',
            'description' => 'Mortgages, personal and business loans — understand the true cost of borrowing.',
        ],
        'credit-cards' => [
            'title' => 'Credit & Cards',
            'order' => 4,
            'icon' => 'credit-card',
            'description' => 'Credit scores, card rewards, and fees explained so you can compare with confidence.',
        ],
    ],

    'authors' => [
        'rachel-bernstein' => [
            'name' => 'Rachel Bernstein',
            'role' => 'Senior Personal Finance & Credit Editor',
            'bio' => 'Rachel has spent over eight years covering consumer banking, credit scoring, and debt management. Before writing full-time, she worked in community financial education, which shapes her no-nonsense, empathetic approach to helping people navigate tricky financial milestones without feeling overwhelmed.',
        ],
        'hannah-cohen' => [
            'name' => 'Hannah Cohen',
            'role' => 'Wealth Management & Investment Writer',
            'bio' => 'Hannah covers long-term financial planning, retirement strategies, and portfolio allocation. She prefers plain-English explanations over industry jargon, helping readers break down complex, long-horizon decisions into manageable, actionable steps.',
        ],
        'lucas-vance' => [
            'name' => 'Lucas Vance',
            'role' => 'Credit, Lending & Wealth Strategies Analyst',
            'bio' => 'Lucas specializes in the intersection of consumer credit, structured financing, and wealth accumulation. With a background blending retail banking analysis and credit advisory, his work helps readers weigh the true cost of borrowing—from mortgages and personal loans to strategic credit card optimization—while balancing those short-term liquidity needs against long-term asset building.',
        ],
        'sofia-martinez' => [
            'name' => 'Sofia Martinez',
            'role' => 'Consumer Finance & Budgeting Writer',
            'bio' => 'Sofia writes about household budgeting, everyday banking, and consumer lending. Her articles are grounded in real-world household constraints rather than idealized spreadsheets, focusing on trade-offs families and individuals actually face when managing tight monthly cash flows.',
        ],
        'claire-odonnell' => [
            'name' => 'Claire O’Donnell',
            'role' => 'Private Wealth & Estate Strategy Analyst',
            'bio' => 'Claire specializes in high-net-worth wealth planning, asset protection, and generational wealth transfer. Coming from a background in private client services, her work focuses on the human side of wealth—helping families navigate legacy planning, tax-efficient investing, and trust structures without getting lost in legal or financial complexity.',
        ],
        'samuel-mensah' => [
            'name' => 'Samuel Mensah',
            'role' => 'Small Business & Strategy Contributor',
            'bio' => 'Sam covers entrepreneurship, cash flow management, and business operations. Drawing from his background advising independent service providers and retail startups, he focuses on the unglamorous, practical hurdles of keeping a small business afloat in changing market conditions.',
        ],
        'ethan-kim' => [
            'name' => 'Ethan Kim',
            'role' => 'Data Operations & Technology Consultant',
            'bio' => 'Ethan looks at how small to mid-sized businesses use internal data and software to streamline operations. He focuses on practical, cost-effective tech adoption rather than chasing enterprise-level tools that don\'t fit everyday business needs.',
        ],
    ],

    'programs' => [
    ],

];
