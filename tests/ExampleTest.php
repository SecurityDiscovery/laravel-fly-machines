<?php

use SecurityDiscovery\LaravelFlyMachines\Helpers\Machine;

it('can test', function () {
    expect(true)->toBeTrue();
});


it('machine builder is empty', function () {
    $machine = Machine::builder()->toArray();

    expect(array_key_exists('name', $machine))->toBeFalse();
    expect(array_key_exists('region', $machine))->toBeFalse();
    expect(count($machine['config']))->toBe(0);
});

it('machine builder sets image', function () {
    $machine = Machine::builder()->image(image: 'test')->toArray();

    expect($machine['config']['image'])->toBe('test');
});

it('machine builder sets name', function () {
    $machine = Machine::builder()->name(name: 'test')->toArray();

    expect($machine['name'])->toBe('test');
});

it('machine builder sets guest', function () {
    expect(
        Machine::builder()->guest(
            cpus: 2, memory_mb: 10
        )->toArray()
    )->toEqual([
        'config' => [
            'guest' => [
                'cpus' => 2,
                'memory_mb' => 10
            ],
        ],
    ]);
    expect(
        Machine::builder()->guest(
            cpus: 2,
        )->toArray()
    )->toEqual([
        'config' => [
            'guest' => [
                'cpus' => 2,
            ],
        ],
    ]);
    expect(
        Machine::builder()->guest(
            kernel_args: ['test'],
        )->toArray()
    )->toEqual([
        'config' => [
            'guest' => [
                'kernel_args' => ['test'],
            ],
        ],
    ]);
});

it('machine builder sets size', function () {
    $machine = Machine::builder()->size(size: 'test')->toArray();

    expect($machine['config']['size'])->toBe('test');
});


it('properly builds env variables using an array', function () {
    $envs = [
        'MY_NAME' => 'MY_VALUE',
    ];
    $machine = Machine::builder()->env(envs: $envs);

    expect($machine->toArray()['config']['env'])->toEqual($envs);
});

it('properly builds env variables using a name and value', function () {
    $machine = Machine::builder()->env(name: 'MY_NAME', value: 'MY_VALUE');

    expect($machine->toArray()['config']['env'])->toEqual(['MY_NAME' => 'MY_VALUE']);
});

