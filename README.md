# A Fly.io Machine PHP API client for Laravel. 

A very simple and thin API client for the Fly.io machine API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/securitydiscovery/laravel-fly-machines.svg?style=flat-square)](https://packagist.org/packages/securitydiscovery/laravel-fly-machines)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/securitydiscovery/laravel-fly-machines/run-tests?label=tests)](https://github.com/securitydiscovery/laravel-fly-machines/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/securitydiscovery/laravel-fly-machines/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/securitydiscovery/laravel-fly-machines/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/securitydiscovery/laravel-fly-machines.svg?style=flat-square)](https://packagist.org/packages/securitydiscovery/laravel-fly-machines)

## Installation

You can install the package via composer:

```bash
composer require securitydiscovery/laravel-fly-machines
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="fly-machines-config"
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
use SecurityDiscovery\LaravelFlyMachines\Facades\LaravelFlyMachines as FlyMachines;
```

#### List machines of a Fly.io App:
```php
FlyMachines::machines('my-fly-app')->list()
```
#### Launch a machine.
```php
FlyMachines::machines('my-fly-app')->launch(machine: $config)
```
#### Update a machine.
```php
FlyMachines::machines('my-fly-app')->update(machineId: "machineId", machine: $config, nonce: "nonce")
```
#### Get a machine.
```php
FlyMachines::machines('my-fly-app')->get(machineId: "machineId")
```
#### Stop a machine.
```php
FlyMachines::machines('my-fly-app')->stop(machineId: "machineId")
```
#### Start a machine.
```php
FlyMachines::machines('my-fly-app')->start(machineId: "machineId")
```
#### Send a signal to a machine.
```php
FlyMachines::machines('my-fly-app')->signal(machineId: "machineId", signal: 9)
```
#### Kill a machine (uses the signal 9 (SIGKILL), the same as the above call).
```php
FlyMachines::machines('my-fly-app')->kill(machineId: "machineId")
```
#### Restart a machine.
```php
FlyMachines::machines('my-fly-app')->restart(machineId: "machineId", forceStop: true, timeout: 10, signal: 9)
```
#### Find a lease for a machine.
```php
FlyMachines::machines('my-fly-app')->findLease(machineId: "machineId")
```
#### Acquire a lease for a machine.
```php
FlyMachines::machines('my-fly-app')->acquireLease(machineId: "machineId", ttl: 30)
```
#### Release a lease of a machine.
```php
FlyMachines::machines('my-fly-app')->releaseLease(machineId: "machineId", nonce: "nonce")
```
#### Wait for a machine.
```php
FlyMachines::machines('my-fly-app')->releaseLease(machineId: "machineId", instanceId: "instanceId", state: "started", timeout: 30)
```
#### Destroy a machine.
```php
FlyMachines::machines('my-fly-app')->destroy(machineId: "machineId", kill: true)
```

#### Launch a Fly.io machine
```php
use SecurityDiscovery\LaravelFlyMachines\Facades\LaravelFlyMachines as FlyMachines;
use SecurityDiscovery\LaravelFlyMachines\Helpers\Machine;

$machineConfig = Machine();
$machine = FlyMachines::machines('my-fly-app')
    ->launch(
        Machine::builder()
            ->image(image: 'registry-1.docker.io/flyio/postgres:14.4')
            ->toArray()
    );
```

#### Launch a Fly.io machine without the helper
```php
use SecurityDiscovery\LaravelFlyMachines\Facades\LaravelFlyMachines as FlyMachines;

$machine = FlyMachines::machines('my-fly-app')->launch([
    'config' => [
        'image' => 'registry-1.docker.io/flyio/postgres:14.4',
    ],
    'region' => 'fra',
]);
```

### Fly Machine Helper
```php
use SecurityDiscovery\LaravelFlyMachines\Facades\LaravelFlyMachines as FlyMachines;
use SecurityDiscovery\LaravelFlyMachines\Helpers\Machine;

$machineConfig = Machine::builder()
    ->image(                                             
        image: 'my.registry.io/test/test:14.4' // Required
    )
    ->init(                                    // Optional
        entrypoint: ['/bin/sh'],
        exec: ['exec'],
        cmd: ['cmd'],
        tty: false
    )
    ->retries(                                 // Optional
        max_retries: 3, 
        policy: 'on-failure'
    )
    ->mount(                                   // Optional
        volume_id: 'vol_123',
        path: '/data'
    )
    
    ->env(name: 'NAME_1', value: 'VALUE_1')  // Optional
    ->env(name: 'NAME_2', value: 'VALUE_2')  // Optional
    
    ->auto_destroy(auto_destroy: True)         // Optional
    ->name(name: 'my_machine')                 // Optional
    ->schedule(schedule: 'daily')              // Optional
    ->region(region: 'fra')                    // Optional
    ->size(size: 'shared-cpu-1x' )             // Optional | WARNING: Use 'guest' or 'size'
    ->guest(                                   // Optional
        cpus: 1,
        memory_mb: 2*256,
        cpu_kind: 'shared',
        kernel_args: []
    )
    ->process()                                // TODO: Document this
    ->toArray();
```

### Example `FlyMachines::machines('my-fly-app')->get('my-machine-id')`
```php
echo json_encode(FlyMachines::machines('my-fly-app')->get('my-machine-id'));
```

See more here [https://fly.io/docs/machines/working-with-machines/](https://fly.io/docs/machines/working-with-machines/).

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


"Laravel" is a registered trademark of Taylor Otwell. This project is not affiliated, associated, endorsed, or sponsored by Taylor Otwell, nor has it been reviewed, tested, or certified by Taylor Otwell. The use of the trademark "Laravel" is for informational and descriptive purposes only. Laravel Workflow is not officially related to the Laravel trademark or Taylor Otwell.
