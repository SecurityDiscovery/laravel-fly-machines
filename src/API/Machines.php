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
     * Launch a Fly machine.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function launch(array $machine)
    {
        return $this->client->post('/machines', $machine)->throw()->json();
    }

    /**
     * Get a Fly machine using the machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $machineId)
    {
        return $this->client->get('/machines/'.$machineId)->throw()->json();
    }

    /**
     * Stop a Fly machine using the machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function stop(string $machineId)
    {
        return $this->client->post('/machines/'.$machineId.'/stop')->throw()->json();
    }

    /**
     * Start a Fly machine using the machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function start(string $machineId)
    {
        return $this->client->post('/machines/'.$machineId.'/start')->throw()->json();
    }

    /**
     * Delete a Fly machine by their machine id.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function destroy(string $machineId, bool $kill)
    {
        $urlParameters = http_build_query(['kill' => $kill]);

        return $this->client->delete('/machines/'.$machineId.'?'.$urlParameters)->throw()->json();
    }
}
