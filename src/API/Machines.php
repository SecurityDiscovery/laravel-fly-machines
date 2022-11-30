<?php

namespace SecurityDiscovery\LaravelFlyMachines\API;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Machines
{
    public PendingRequest $client;

    public function __construct(string $appName)
    {
        $baseUrl = sprintf('%s://%s/v1/apps/%s',
            config('fly-machines.proto'),
            config('fly-machines.endpoint'),
            $appName
        );
        $this->client = Http::withHeaders([
            'Authorization' => 'Bearer '.config('fly-machines.token'),
        ])
            ->acceptJson()
            ->baseUrl($baseUrl);
    }

    /**
     * List Machines of a Fly.io App.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function list()
    {
        return $this->client->get('/machines')->throw()->json();
    }

    /**
     * Create a Fly machine.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function create(array $machine)
    {
        return $this->client->post('/machines', $machine)->throw()->json();
    }

    /**
     * Get a Fly machine by their machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $machineId)
    {
        return $this->client->get('/machines/'.$machineId)->throw()->json();
    }

    /**
     * Delete a Fly machine by their machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function delete(string $machineId)
    {
        return $this->client->delete('/machines/'.$machineId)->throw()->json();
    }
}
