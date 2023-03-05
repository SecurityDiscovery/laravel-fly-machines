<?php

namespace SecurityDiscovery\LaravelFlyMachines\Helpers;

use Illuminate\Http\Client\PendingRequest;

class HTTPClient extends PendingRequest
{
    public function __construct(string $appName)
    {
        parent::__construct();

        $baseUrl = sprintf('%s://%s/v1/apps/%s',
            config('fly-machines.proto'),
            config('fly-machines.endpoint'),
            $appName
        );

        $this->baseUrl($baseUrl);
        $this->withToken(config('fly-machines.token'));
        $this->acceptJson();
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param  array|string|null  $query
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function get(string $url, $query = null): mixed
    {
        return $this->send('GET', $url, func_num_args() === 1 ? [] : [
            'query' => $query,
        ]);
    }

    /**
     * Issue a HEAD request to the given URL.
     *
     * @param  array|string|null  $query
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function head(string $url, $query = null): mixed
    {
        return $this->send('HEAD', $url, func_num_args() === 1 ? [] : [
            'query' => $query,
        ]);
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param  array  $data
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function post(string $url, $data = []): mixed
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a PATCH request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function patch($url, $data = []): mixed
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a PUT request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function put($url, $data = []): mixed
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a DELETE request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function delete($url, $data = []): mixed
    {
        return $this->send('DELETE', $url, empty($data) ? [] : [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Exception
     */
    public function send(string $method, string $url, array $options = []): mixed
    {
        return parent::send($method, $url, $options)->throw()->json();
    }
}
