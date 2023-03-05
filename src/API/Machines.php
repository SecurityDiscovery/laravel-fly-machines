<?php

namespace SecurityDiscovery\LaravelFlyMachines\API;

use SecurityDiscovery\LaravelFlyMachines\Helpers\HTTPClient;

class Machines
{
    private static string $FLY_NONCE_HEADER = 'fly-machine-lease-nonce';

    public HTTPClient $client;

    public function __construct(string $appName)
    {
        $this->client = new HTTPClient($appName);
    }

    /**
     * List machines of a Fly.io App.
     *
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function list(): mixed
    {
        return $this->client->get(url: '/machines');
    }

    /**
     * Get a machine.
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $machineId): mixed
    {
        return $this->client->get(url: '/machines/'.$machineId);
    }

    /**
     * Launch a machine.
     *
     * @param array $machine The machine config.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function launch(array $machine): mixed
    {
        return $this->client->post(url: '/machines', data: $machine);
    }

    /**
     * Update a machine.
     *
     * @param string $machineId The machine id.
     * @param array $machine The machine config.
     * @param string|null $nonce The nonce
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function update(string $machineId, array $machine, ?string $nonce): mixed
    {
        $headers = [];
        if ($nonce) {
            $headers = [self::$FLY_NONCE_HEADER => $nonce];
        }

        return $this->client
            ->withHeaders($headers)
            ->post(url: '/machines/'.$machineId, data: $machine);
    }

    /**
     * Stop a machine.
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function stop(string $machineId): mixed
    {
        return $this->client->post(url: '/machines/'.$machineId.'/stop');
    }

    /**
     * Start a machine.
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function start(string $machineId): mixed
    {
        return $this->client->post(url: '/machines/'.$machineId.'/start');
    }

    /**
     * Kill a machine using the signal 9 (SIGKILL).
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function kill(string $machineId): mixed
    {
        return $this->signal(machineId: $machineId, signal: 9);
    }

    /**
     * Send a signal to a machine.
     *
     * @param string $machineId The machine id.
     * @param int $signal The signal to send. E.g. SIGKILL = 9.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function signal(string $machineId, int $signal): mixed
    {
        return $this->client->post(url: '/machines/'.$machineId.'/signal', data: [
            'signal' => $signal,
        ]);
    }

    /**
     * Restart a machine.
     *
     * @param string $machineId The machine id.
     * @param bool $forceStop Force stop the machine.
     * @param int|null $timeout Timeout
     * @param string|null $signal The signal
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function restart(string $machineId, bool $forceStop, ?int $timeout, ?string $signal): mixed
    {
        $query = http_build_query([
            'force_stop' => $forceStop,
            'timeout' => $timeout,
            'signal' => $signal,
        ]);

        return $this->client->post(url: '/machines/'.$machineId.'/restart?'.$query);
    }

    /**
     * Find a lease for a machine.
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function findLease(string $machineId): mixed
    {
        return $this->client->get(url: '/machines/'.$machineId.'/lease');
    }

    /**
     * Acquire a lease of a machine.
     *
     * @param string $machineId The machine id.
     * @param int|null $ttl Seconds to lease individual machines while running deployment.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function acquireLease(string $machineId, ?int $ttl): mixed
    {
        $query = http_build_query([
            'ttl' => $ttl,
        ]);

        return $this->client->post(url: '/machines/'.$machineId.'/lease?'.$query);
    }

    /**
     * Release a lease of a machine.
     *
     * @param string $machineId The machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function releaseLease(string $machineId, ?string $nonce): mixed
    {
        $headers = [];
        if ($nonce) {
            $headers = [self::$FLY_NONCE_HEADER => $nonce];
        }

        return $this->client->withHeaders(headers: $headers)
            ->delete(url: '/machines/'.$machineId.'/lease');
    }

    /**
     * Wait for a machine.
     *
     * @param string $machineId The machine id.
     * @param string $instanceId The machine instance id.
     * @param string $state The machine state to wait for. Default is "started".
     * @param int $timeout How long to wait for the state.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function wait(string $machineId, string $instanceId, string $state = 'started', int $timeout = 60): mixed
    {
        $query = [
            'instance_id' => $instanceId,
            'timeout' => $timeout,
            'state' => $state,
        ];

        return $this->client->get(url: '/machines/'.$machineId.'/wait', query: $query);
    }

    /**
     * Delete a machine by their machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function destroy(string $machineId, bool $kill): mixed
    {
        $query = http_build_query([
            'kill' => $kill ? 'true' : null,
            'force' => $kill ? 'true' : null,
        ]);

        return $this->client->delete(url: '/machines/'.$machineId.'?'.$query);
    }
}
