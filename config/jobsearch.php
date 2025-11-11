<?php

return [
    'jobsearch_az' => [
        'base_url' => env('JOBSEARCH_AZ_BASE', 'https://classic.jobsearch.az'),
        'listing_path' => '/?page=%d',
        'limit' => env('JOBSEARCH_AZ_LIMIT', 50),

        // CSS selectors for DOM parsing. Adjust if the upstream markup changes.
        'selectors' => [
            'job_card' => '.vacancies__item',
            'title' => '.vacancies__title a',
            'company' => '.vacancies__provided span',
            'link' => '.vacancies__title a',
            'published' => 'li.d-none.d-lg-block',
            'deadline' => 'li.d-none.d-lg-block',
            'detail_description' => '.vacancy__description .content',
            'detail_deadline' => '.vacancy__dead-line',
            'detail_company' => '.company__title h2',
            'detail_location' => '.vacancy__location',
            'detail_published' => '.vacancy__publish-date',
        ],
    ],
];
