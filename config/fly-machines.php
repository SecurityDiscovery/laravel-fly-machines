<?php

// config for SecurityDiscovery/LaravelFlyMachines
return [
    'proto' => env('FLY_API_PROTO', 'https'),
    // The endpoint to the Fly machines API.
    'endpoint' => env('FLY_API_HOSTNAME', 'api.machines.dev'),
    // The token to authenticate to the API.
    'token' => env('FLY_API_TOKEN'),
];
