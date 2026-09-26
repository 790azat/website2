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
        [
            'slug' => 'truist-auto-financing',
            'title' => 'Truist Auto Financing: The Full Guide to Auto Loans, Refinancing and Applications',
            'section' => 'loans-financing',
            'date' => '2026-09-25',
            'intro' => 'Truist Auto Financing provides vehicle financing options for eligible borrowers, with online application tools and financing resources for purchasing or refinancing a vehicle.',
            'cta_label' => 'Learn More',
            'cta_url' => 'https://www.truist.com/loans/auto-loans',
            'hero_icon' => 'truck',
            'hero_image' => 'programs/truist-auto-financing.webp',
            'hero_tagline' => 'Everything You Need to Know: Truist Auto Financing',
            'overview_heading' => 'Truist Auto Loans, Vehicle Financing and Refinancing Options',
            'overview_intro' => 'Truist offers several auto-financing options that can help borrowers purchase a vehicle or explore refinancing. Available terms and rates depend on factors such as credit history, loan amount, vehicle, and other application details.',
            'features' => [
                [
                    'icon' => 'document-text',
                    'title' => 'Convenient Auto Loan Application',
                    'body' => 'The Truist auto loan application process gives borrowers a way to apply for vehicle financing and explore available loan options. Applicants can review their financing needs and provide the information required for credit consideration.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'truck',
                    'title' => 'Vehicle Financing Options',
                    'body' => 'Truist vehicle loans can help eligible borrowers finance qualifying vehicles. Truist car financing may be available for different vehicle purchases, with loan terms and approval determined by individual circumstances.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'arrow-path',
                    'title' => 'Auto Loan Refinancing',
                    'body' => 'Borrowers searching for Truist auto loan refinance or refinance car loan Truist can explore whether refinancing may help change their existing financing. Refinancing replaces an existing auto loan with a new loan, so borrowers should compare the new rate, monthly payment, fees, and total borrowing cost.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'receipt-percent',
                    'title' => 'Refinance Rates',
                    'body' => 'Truist auto loan refinance rates can vary according to factors such as credit profile, loan amount, vehicle, and other lending criteria. Comparing the new annual percentage rate with the existing loan can help determine the potential impact of refinancing.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'check-badge',
                    'title' => 'Pre-Approval for a Car Loan',
                    'body' => 'Borrowers searching for a Truist pre approval car loan can explore available financing before completing a vehicle purchase. Pre-approval can help shoppers understand their potential financing range and budget when comparing vehicles.',
                    'list' => null,
                    'note' => null,
                ],
            ],
            'pros' => [
                [
                    'title' => 'Multiple Financing Options',
                    'description' => 'Truist provides options for auto financing, vehicle loans, and refinancing, allowing borrowers to explore different ways to finance or restructure a vehicle loan.',
                ],
                [
                    'title' => 'Online Application Convenience',
                    'description' => 'The Truist auto loan application process provides a convenient way for borrowers to begin exploring vehicle financing without relying entirely on an in-branch process.',
                ],
                [
                    'title' => 'Refinancing Opportunities',
                    'description' => 'Borrowers researching Truist car refinance or Truist auto loan refinance rates can compare potential new financing against their existing loan to evaluate whether refinancing fits their needs.',
                ],
            ],
            'extra_sections' => [],
            'related_slug' => 'bad-credit-car-loans-and-auto-refinancing-options',
        ],
        [
            'slug' => 'santander-auto-financing',
            'title' => 'Santander Auto Loans: The Full Guide to Applications, Pre-Approval and Refinancing',
            'section' => 'loans-financing',
            'date' => '2026-09-25',
            'intro' => 'Santander Auto Loans provide financing options for eligible new and used vehicles through participating dealerships, with pre-qualification tools, online payment calculators, and convenient application options.',
            'cta_label' => 'View More Information',
            'cta_url' => 'https://santanderconsumerusa.com/auto-loans',
            'hero_icon' => 'truck',
            'hero_image' => 'programs/santander-auto-financing.webp',
            'hero_tagline' => 'Everything You Need to Know: Santander Auto Loans',
            'overview_heading' => 'Santander Consumer USA Auto Financing Tools and Options',
            'overview_intro' => 'Santander Consumer USA offers several tools and financing options designed to help shoppers explore vehicle financing and manage their auto loans.',
            'features' => [
                [
                    'icon' => 'check-badge',
                    'title' => 'Auto Loan Pre-Qualification',
                    'body' => "Santander's Drive platform allows shoppers to pre-qualify for auto financing before selecting a vehicle. The initial pre-qualification uses a soft credit inquiry that does not affect the consumer's credit score.",
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'truck',
                    'title' => 'New and Used Vehicle Financing',
                    'body' => 'Santander Consumer USA provides financing for eligible new and used vehicles through participating dealerships. Available financing and final terms can vary based on the borrower, vehicle, and credit application.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'document-text',
                    'title' => 'Convenient Auto Loan Application',
                    'body' => 'A Santander auto loan application can be completed through participating dealerships. Starting with pre-qualification can help shoppers explore potential financing before completing a full credit application.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'calculator',
                    'title' => 'Payment & Finance Calculators',
                    'body' => 'Santander provides online auto finance calculators that can help borrowers estimate monthly payments and understand how different loan amounts and terms may affect their budget.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'computer-desktop',
                    'title' => 'Online Account Management',
                    'body' => 'Santander customers can use online account tools to manage their auto-finance accounts, review payment information, and make payments conveniently.',
                    'list' => null,
                    'note' => null,
                ],
            ],
            'pros' => [
                [
                    'title' => 'Pre-Qualification Option',
                    'description' => "Santander's pre-qualification process allows shoppers to explore potential financing before choosing a vehicle, with the initial soft inquiry not affecting their credit score.",
                ],
                [
                    'title' => 'Financing for New and Used Vehicles',
                    'description' => 'Eligible borrowers can access Santander Consumer USA auto financing for new and used vehicles through participating dealerships.',
                ],
                [
                    'title' => 'Convenient Financing Tools',
                    'description' => 'Online calculators and account-management tools can help shoppers estimate payments and manage their Santander car loan after financing.',
                ],
            ],
            'extra_sections' => [],
            'related_slug' => 'used-car-financing-what-to-check-before-financing-a-pre-owned-vehicle',
        ],
        [
            'slug' => 'ford-auto-financing',
            'title' => 'Ford Auto Financing: The Full Guide to Rates, Loans and Ford Credit',
            'section' => 'loans-financing',
            'date' => '2026-09-25',
            'intro' => 'Ford Auto Financing through Ford Credit offers financing and leasing options for eligible Ford vehicles, with online prequalification, payment calculators, credit applications, and programs designed for different purchasing needs.',
            'cta_label' => 'See More Details',
            'cta_url' => 'https://www.ford.com/finance/',
            'hero_icon' => 'truck',
            'hero_image' => 'programs/ford-auto-financing.webp',
            'hero_tagline' => 'Everything You Need to Know: Ford Auto Financing',
            'overview_heading' => 'Ford Credit Financing, Leasing and Prequalification Options',
            'overview_intro' => 'Ford Credit provides several ways to finance or lease a Ford, from traditional vehicle purchases to flexible payment structures and lease programs. Borrowers can also explore their potential budget before visiting a dealership.',
            'features' => [
                [
                    'icon' => 'building-library',
                    'title' => 'Ford Credit Financing',
                    'body' => 'Ford Motor Credit financing provides options for purchasing or leasing eligible Ford vehicles. Ford Credit currently offers financing programs including Standard Purchase, Flex Buy®, and Red Carpet Lease, with the available option depending on the transaction and vehicle.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'check-badge',
                    'title' => 'Ford Prequalification',
                    'body' => "Ford Credit allows shoppers to get prequalified before shopping for a vehicle. Ford states that its prequalification process takes only a few minutes and does not impact the consumer's credit score.",
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'document-text',
                    'title' => 'Online Auto Loan Application',
                    'body' => 'A Ford auto financing application can be completed online. Ford Credit says applicants can submit a full credit application online, while financing is ultimately completed through a participating Ford dealer.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'receipt-percent',
                    'title' => 'Ford Car Loan Interest Rates',
                    'body' => 'Your Ford car loan interest rate depends on factors such as the financing transaction, vehicle, and credit profile. Ford also periodically offers promotional financing rates and other incentives, so available offers can vary by vehicle, location, and eligibility.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'tag',
                    'title' => '0% Ford Financing Offers',
                    'body' => 'Shoppers searching for Ford 0 financing are generally looking for promotional APR offers. These offers are not necessarily available on every Ford model or at all times, and eligibility and offer terms can vary. Checking current Ford Credit offers before purchasing is important when comparing promotional financing.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'adjustments-horizontal',
                    'title' => 'Flexible Ford Financing Options',
                    'body' => "Ford Credit's Standard Purchase provides fixed monthly payments, while Flex Buy® uses an unequal payment structure with lower payments during the first 36 months. Red Carpet Lease provides another option for customers who prefer leasing rather than purchasing.",
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'user-plus',
                    'title' => 'Ford Credit for First-Time or Limited-Credit Buyers',
                    'body' => "Ford's First-Time Buyer Program is designed for eligible consumers financing their first vehicle, including applicants with limited credit history. The current program includes potential lower-APR eligibility, bonus cash on qualifying new Ford vehicles, and financing terms of up to 84 months for eligible applicants.",
                    'list' => null,
                    'note' => null,
                ],
            ],
            'pros' => [
                [
                    'title' => 'Online Prequalification',
                    'description' => 'Ford Credit lets shoppers get prequalified before visiting a dealership, helping them estimate what they may be able to afford without an initial impact to their credit score.',
                ],
                [
                    'title' => 'Multiple Financing Options',
                    'description' => 'Ford Credit offers Standard Purchase, Flex Buy®, and Red Carpet Lease, giving shoppers different ways to finance or lease an eligible Ford vehicle.',
                ],
                [
                    'title' => 'Financing Programs for Different Credit Situations',
                    'description' => "Eligible first-time buyers and consumers with limited credit history may qualify for Ford's First-Time Buyer Program, subject to the program's requirements and underwriting.",
                ],
            ],
            'extra_sections' => [],
            'related_slug' => 'auto-loans-rates-terms-monthly-payments-and-preapproval',
        ],
        [
            'slug' => 'sofi-loans',
            'title' => 'SoFi Loans: A Full Guide to Personal, Student and Refinancing Options',
            'section' => 'loans-financing',
            'date' => '2026-09-25',
            'intro' => 'SoFi offers personal loans, private student loans, and student loan refinancing, with online prequalification, competitive-rate options, and digital tools for borrowers comparing financing and repayment choices.',
            'cta_label' => 'Learn More',
            'cta_url' => 'https://www.sofi.com/',
            'hero_icon' => 'academic-cap',
            'hero_image' => 'programs/sofi-loans.webp',
            'hero_tagline' => 'Everything You Need to Know: SoFi Loans',
            'overview_heading' => 'SoFi Personal Loans, Student Loans and Refinancing',
            'overview_intro' => "SoFi's lending products cover several borrowing needs, from education expenses and personal borrowing to refinancing existing student debt.",
            'features' => [
                [
                    'icon' => 'banknotes',
                    'title' => 'Personal Loans',
                    'body' => 'SoFi personal loans are unsecured installment loans that can be used for eligible personal, family, or household expenses, including debt consolidation and other qualified needs. Borrowers can check potential rates through prequalification before submitting a full application.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'academic-cap',
                    'title' => 'Private Student Loans',
                    'body' => 'SoFi private student loans are available for eligible undergraduate and graduate students and can help cover qualified education costs. SoFi offers prequalification using a soft credit pull, allowing applicants to check potential eligibility without affecting their credit score.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'arrow-path',
                    'title' => 'Student Loan Refinancing',
                    'body' => "SoFi student loan refinancing allows eligible borrowers to refinance qualified federal and private education loans through a new private loan. Eligibility depends on factors including the borrower's education history, financial profile, and loan type.",
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'check-badge',
                    'title' => 'Student Loan Prequalification',
                    'body' => 'Borrowers searching for SoFi student loans prequalify can check potential rates and terms through a soft credit pull. Prequalification is not a guarantee of approval, and continuing with a full application can involve a hard credit inquiry.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'building-library',
                    'title' => 'Undergraduate and Graduate Student Loans',
                    'body' => 'SoFi offers undergraduate student loans and graduate student loans for eligible students. Loan terms, rates, eligibility requirements, and repayment options can vary depending on the program and borrower.',
                    'list' => null,
                    'note' => null,
                ],
                [
                    'icon' => 'squares-2x2',
                    'title' => 'Consolidating and Refinancing Student Debt',
                    'body' => 'Borrowers searching for SoFi consolidate student loans, SoFi consolidate loans, or SoFi student debt may be considering refinancing multiple education loans into a new loan. SoFi notes that refinancing can affect federal-loan benefits, including certain federal repayment and forgiveness programs, so borrowers should review those tradeoffs before refinancing.',
                    'list' => null,
                    'note' => null,
                ],
            ],
            'pros' => [
                [
                    'title' => 'Soft Credit Prequalification',
                    'description' => 'SoFi allows eligible applicants to check potential rates and terms using a soft credit pull that does not affect their credit score. A full application may involve a hard credit inquiry.',
                ],
                [
                    'title' => 'Multiple Student Loan Options',
                    'description' => 'SoFi offers private student loans, undergraduate student loans, graduate student loans, and student loan refinancing, giving borrowers different options depending on whether they are financing education or managing existing education debt.',
                ],
                [
                    'title' => 'Personal and Student Financing',
                    'description' => 'In addition to student lending, SoFi personal loans provide another borrowing option for eligible personal, family, or household expenses, including debt consolidation.',
                ],
            ],
            'extra_sections' => [],
            'related_slug' => 'private-student-loans-a-guide-to-education-financing',
        ],
    ],

];
