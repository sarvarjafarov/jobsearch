<?php

return [
    'enabled' => (bool) env('GOOGLE_INDEXING_ENABLED', false),
    'credentials' => env('GOOGLE_INDEXING_CREDENTIALS'), // absolute path or storage path
    'scopes' => [
        'https://www.googleapis.com/auth/indexing',
    ],
];
