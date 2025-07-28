<?php

return [
    'header' => [
        'key' => env('API_HEADER_KEY', 'X-SAMPL-SECRET'),
    ],
    'controllers' => [
        'query-keys' => [
            'offset' => 'offset',
            'limit' => 'limit',
            'filterBy' => 'filterBy',
            'sortBy' => 'sortBy',
        ],
        'settings' => [
            'offset-min' => 0,
            'limit-max' => 10,
        ],
    ],
];
