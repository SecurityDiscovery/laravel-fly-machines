<?php

use SecurityDiscovery\LaravelFlyMachines\Helpers\Machine;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('properly builds env variables', function () {
    $machine = Machine::builder()->env(envs: [
        'MY_NAME' => 'MY_VALUE',
    ]);

    dd($machine);
});
