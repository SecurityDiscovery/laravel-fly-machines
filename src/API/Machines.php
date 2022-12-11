<?php

namespace SecurityDiscovery\LaravelFlyMachines\API;

use SecurityDiscovery\LaravelFlyMachines\Helpers\HTTPClient;

class Machines
{
    public HTTPClient $client;

    private static string $FLY_NONCE_HEADER = 'fly-machine-lease-nonce';

    public function __construct(string $appName)
    {
        $this->client = new HTTPClient($appName);
    }

    /**
     * List machines of a Fly.io App.
     *
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function list(): mixed
    {
        return $this->client->get(url: '/machines');
    }

    /**
     * Launch a machine.
     *
     * @param  array  $machine The machine config.
     * @return mixed
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
     * @param  string  $machineId The machine id.
     * @param  array  $machine The machine config.
     * @param  string  $nonce The nonce. If == '', we don't send it.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function update(string $machineId, array $machine, string $nonce = ''): mixed
    {
        $headers = [];
        if ($nonce != '') {
            $headers = [self::$FLY_NONCE_HEADER => $nonce];
        }

        return $this->client
                ->withHeaders($headers)
                ->post(url: '/machines/'.$machineId, data: $machine);
    }

    /**
     * Get a machine.
     *
     * @param  string  $machineId The machine id.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $machineId): mixed
    {
        return $this->client->get(url: '/machines/'.$machineId);
    }

    /**
     * Stop a machine.
     *
     * @param  string  $machineId The machine id.
     * @return mixed
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
     * @param  string  $machineId The machine id.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function start(string $machineId): mixed
    {
        return $this->client->post(url: '/machines/'.$machineId.'/start');
    }

    /**
     * Send a signal to a machine.
     *
     * @param  string  $machineId The machine id.
     * @param  int  $signal The signal to send. E.g. SIGKILL = 9.
     * @return mixed
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
     * Kill a machine using the signal 9 (SIGKILL).
     *
     * @param  string  $machineId The machine id.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function kill(string $machineId): mixed
    {
        return $this->signal(machineId: $machineId, signal: 9);
    }

    /**
     * Restart a machine.
     *
     * @param  string  $machineId The machine id.
     * @param  bool  $forceStop Force stop the machine.
     * @param  int  $timeout Timeout. If == -1, we don't send that value.
     * @param  string  $signal The signal. If == '', we don't send that value.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function restart(string $machineId, bool $forceStop, int $timeout = -1, string $signal = ''): mixed
    {
        $queryParams = [
            'force_stop' => $forceStop,
        ];
        if ($timeout != -1) {
            $queryParams['timeout'] = $timeout;
        }
        if ($signal != '') {
            $queryParams['signal'] = $signal;
        }

        return $this->client->post(url: '/machines/'.$machineId.'/restart?'.http_build_query($queryParams));
    }

    /**
     * Find a lease for a machine.
     *
     * @param  string  $machineId The machine id.
     * @return mixed
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
     * @param  string  $machineId The machine id.
     * @param  int  $ttl If ttl != -1, we send the value.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function acquireLease(string $machineId, int $ttl = -1): mixed
    {
        $queryParams = [];
        if ($ttl != -1) {
            $queryParams['ttl'] = $ttl;
        }

        return $this->client->post(url: '/machines/'.$machineId.'/lease?'.http_build_query($queryParams));
    }

    /**
     * Release a lease of a machine.
     *
     * @param  string  $machineId The machine id.
     * @param  string  $nonce The nonce. If == '', we don't send it.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function releaseLease(string $machineId, string $nonce = ''): mixed
    {
        $headers = [];
        if ($nonce != '') {
            $headers = [self::$FLY_NONCE_HEADER => $nonce];
        }

        return $this->client->withHeaders(headers: $headers)
            ->delete(url: '/machines/'.$machineId.'/lease');
    }

    /**
     * Wait for a machine.
     *
     * @param  string  $machineId The machine id.
     * @param  string  $instanceId The machine instance id.
     * @param  string  $state The machine state to wait for. Default is "started".
     * @param  int  $timeout How long to wait for the state.
     * @return mixed
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function wait(string $machineId, string $instanceId, string $state = 'started', int $timeout = 30): mixed
    {
        $queryParams = [
            'instance_id' => $instanceId,
            'timeout' => $timeout,
            'state' => $state,
        ];

        return $this->client->get(url: '/machines/'.$machineId.'/wait', query: $queryParams);
    }

    /**
     * Delete a machine by their machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function destroy(string $machineId, bool $kill): mixed
    {
        $queryParams = [];
        if ($kill) {
            $queryParams['kill'] = 'force';
        }

        return $this->client->delete(url: '/machines/'.$machineId.'?'.http_build_query($queryParams));
    }
}
