<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file", "memcached", "redis"
    |
    */

    'video_types' => [
    	1 => 'Instructional',
    	2 => 'Performance',
    	3 => 'Show off',
    ],

    'keywords' => ['dance', 'learn', 'community', 'tutorial'],

    'weekly_challenge_statuses' => [
        0 => 'Draft',
        1 => 'Active',
        2 => 'Closed'
    ]
];
