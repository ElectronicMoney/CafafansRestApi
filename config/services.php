<?php
/**
 * Fetching the list of from the micro service
 * @param void
 * @return string $base_uri
 */
return [
    'example_service' =>  [
        'base_uri'  => env('EXAMPLE_SERVICE_BASE_URL'),
        'api_access_token' => env('API_ACCESS_TOKEN'),
    ]
];

