<?php
return [
    'local'      => [
        'base_uri' => env('LOCAL_SOLAR_BASE_URL'),
        'api_key' => env('API_SUCRITE_KEY')
    ],
    'production' => [
        'base_uri' => env('SOLAR_BASE_URL'),
        'api_key' => env('API_SUCRITE_KEY')
    ],
    'pm' => [
        'base_uri' => env('PM_BASE_URL'),
        'api_key' => env('API_SUCRITE_KEY')
    ],
    'crm' => [
        'base_uri' => env('CRM_BASE_URL'),
        'api_key' => env('API_SUCRITE_KEY')
    ]

];
