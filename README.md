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

use \SecurityDiscovery\LaravelFlyMachines\Facades\LaravelFlyMachines as FlyMachines;

// List Fly machines...
$machines = FlyMachines::machines('my-fly-app')->list();

// Create a Fly machine...
$machine = FlyMachines::machines('my-fly-app')->create([
    'name' => 'this-is-my-machine-name' // Not required per official Fly.io documentation. Fly will generate a name for you
    'config' => [] // @TODO add example fly machine config! REQUIRED
]);

// Get a Fly machine by their machine id.
$machine = FlyMachines::machines('my-fly-app')->get('my-machine-id');

// Delete a Fly machine...
// Note: The machine id != machine name.
FlyMachines::machines('my-fly-app')->delete('my-machine-id');


// E.g. can be useful to check the state of the machine later.
$allMachines = FlyMachines::machines('securitydiscovery-db')->list();
$firstMachineId = $allMachines[0]['id']; // e.g. save the id in a db
$firstMachine = FlyMachines::machines('securitydiscovery-db')->get($firstMachineId);
```

### Example `FlyMachines::machines('my-fly-app')->get('my-machine-id')`
```php
echo json_encode(FlyMachines::machines('my-fly-app')->get('my-machine-id'));
```
```json
{
  "id": "148e127*****",
  "name": "*****-leaf-*****",
  "state": "started",
  "region": "fra",
  "instance_id": "01GH8YVWQ*****JK96N*****D9M",
  "private_ip": "fdaa:0:af8e:*****",
  "config": {
    "env": {
      "PRIMARY_REGION": "fra"
    },
    "init": {
      "exec": null,
      "entrypoint": null,
      "cmd": null,
      "tty": false
    },
    "image": "registry-1.docker.io/flyio/postgres:14.4",
    "metadata": {
      "managed-by-fly-deploy": "true"
    },
    "mounts": [
      {
        "encrypted": true,
        "path": "/data",
        "size_gb": 1,
        "volume": "vol_52e*****3p*****"
      }
    ],
    "restart": {
      "policy": "always"
    },
    "guest": {
      "cpu_kind": "shared",
      "cpus": 1,
      "memory_mb": 1024
    },
    "metrics": {
      "port": 9187,
      "path": "/metrics"
    },
    "checks": {
      "pg": {
        "type": "http",
        "port": 5500,
        "interval": "15s",
        "timeout": "10s",
        "method": "",
        "path": "/flycheck/pg"
      },
      "role": {
        "type": "http",
        "port": 5500,
        "interval": "15s",
        "timeout": "10s",
        "method": "",
        "path": "/flycheck/role"
      },
      "vm": {
        "type": "http",
        "port": 5500,
        "interval": "1m0s",
        "timeout": "10s",
        "method": "",
        "path": "/flycheck/vm"
      }
    }
  },
  "image_ref": {
    "registry": "registry-1.docker.io",
    "repository": "flyio/postgres",
    "tag": "14.4",
    "digest": "sha256:9daaa15119742e5777f5480ef476024e8827016718b5b020ef33a5fb084b60e8",
    "labels": {
      "fly.app_role": "postgres_cluster",
      "fly.pg-version": "14.4-1.pgdg110+1",
      "fly.version": "v0.0.32"
    }
  },
  "created_at": "2022-11-05T15:36:21Z",
  "updated_at": "2022-11-22T21:17:53Z",
  "events": [
    {
      "id": "01GJGK8*****70P1QHJR",
      "type": "start",
      "status": "started",
      "source": "flyd",
      "timestamp": 1669151873327
    },
    {
      "id": "01GJGK8*****PYYHVW722T",
      "type": "start",
      "status": "started",
      "source": "flyd",
      "timestamp": 1669151873101
    },
    {
      "id": "01GJGK8NP*****9FZXSSNQ1F",
      "type": "restart",
      "status": "starting",
      "source": "flyd",
      "timestamp": 1669151872710
    },
    {
      "id": "01GJGK8ND*****660JC1BRR",
      "type": "exit",
      "status": "stopped",
      "request": {
        "MonitorEvent": {
          "exit_event": {
            "requested_stop": true,
            "guest_signal": -1,
            "signal": -1,
            "exited_at": "2022-11-22T21:17:51.924Z"
          }
        }
      },
      "source": "flyd",
      "timestamp": 1669151872435
    },
    {
      "id": "01GJGK8KE*****ZX3ZAP8",
      "type": "restart",
      "status": "stopping",
      "source": "user",
      "timestamp": 1669151870426
    }
  ],
  "checks": [
    {
      "name": "pg",
      "status": "passing",
      "output": "[✓] transactions: read/write (353.78µs)\n[✓] connections: 12 used, 3 reserved, 10000 max (3.71ms)",
      "updated_at": "2022-11-28T13:03:13Z"
    },
    {
      "name": "vm",
      "status": "passing",
      "output": "[✓] checkDisk: 2.89 GB (73.8%) free space on /data/ (20.63µs)\n[✓] checkLoad: load averages: 0.68 0.37 0.21 (62.47µs)\n[✓] memory: system spent 372ms of the last 60s waiting on memory (51.63µs)\n[✓] cpu: system spent 2.08s of the last 60s waiting on cpu (23.31µs)\n[✓] io: system spent 138ms of the last 60s waiting on io (21.04µs)",
      "updated_at": "2022-11-22T22:25:29Z"
    },
    {
      "name": "role",
      "status": "passing",
      "output": "leader",
      "updated_at": "2022-11-22T21:53:00Z"
    }
  ]
}
```

### Example Create Machine
```json
{
  "name": "machine-syd",
  "config": {
    "image": "flyio/fastify-functions",
    "env": {
      "APP_ENV": "production"
    },
    "services": [
      {
        "ports": [
          {
            "port": 443,
            "handlers": [
              "tls",
              "http"
            ]
          },
          {
            "port": 80,
            "handlers": [
              "http"
            ]
          }
        ],
        "protocol": "tcp",
        "internal_port": 8080
      }
    ]
  },
  "region": "syd"
}
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
