# A Fly.io Machine PHP API client for Laravel. 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/securitydiscovery/laravel-fly-machines.svg?style=flat-square)](https://packagist.org/packages/securitydiscovery/laravel-fly-machines)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/securitydiscovery/laravel-fly-machines/run-tests?label=tests)](https://github.com/securitydiscovery/laravel-fly-machines/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/securitydiscovery/laravel-fly-machines/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/securitydiscovery/laravel-fly-machines/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/securitydiscovery/laravel-fly-machines.svg?style=flat-square)](https://packagist.org/packages/securitydiscovery/laravel-fly-machines)

# Package in development. Not recommended for actual usage.

## Installation

You can install the package via composer:

```bash
composer require securitydiscovery/laravel-fly-machines
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-fly-machines-config"
```

This is the contents of the published config file:

```php
// config for SecurityDiscovery/LaravelFlyMachines
return [
    'proto' => env('FLY_API_PROTO', 'http'),
    // The endpoint to the Fly machines API.
    'endpoint' => env('FLY_API_HOSTNAME', '127.0.0.1:4280'),
    // The token to authenticate to the API.
    'token' => env('FLY_API_TOKEN'),
];

```

## Usage

```php
$laravelFlyMachines = new SecurityDiscovery\LaravelFlyMachines();

// List Fly machines...
$machines = $laravelFlyMachines->machines('my-fly-app')->list();

// Create a Fly machine...
$machine = $laravelFlyMachines->machines('my-fly-app')->create([
    'name' => 'this-is-my-machine-name'
    'config' => [] // @TODO add example fly machine config! REQUIRED
]);

$machine = $laravelFlyMachines->machines('my-fly-app')->get('my.machine-id');
// Delete a Fly machine...
// Note: The machine id != machine name.
$laravelFlyMachines->machines('my-fly-app')->delete('my-machine-id');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sebastien Kaul](https://github.com/KaulSe)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
