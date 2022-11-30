<?php

namespace SecurityDiscovery\LaravelFlyMachines\Helpers;

class Machine
{
    /**
     * @var string The docker image
     */
    private string $image;

    /**
     * @var string The Machine name
     */
    private string $name = '';

    /**
     * @var string The Fly.io region.
     */
    private string $region = '';

    /**
     * @var array Environment variables
     */
    private array $env = [];

    /**
     * @var array The restart policy
     */
    private array $restart = [];

    /**
     * @var array The container initial command.
     */
    private array $init = [];

    /**
     * @var array The machine itself. Cpu, Memory etc.
     */
    private array $guest = ['cpu_kind' => 'shared'];

    /**
     * @param  string  $image The docker image
     */
    public function __construct(string $image)
    {
        $this->image = $image;
    }

    /**
     * Set the image of the Fly machine.
     *
     * @param  string  $image
     * @return $this
     */
    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Set the image of the Fly machine.
     *
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the maximum retries of a container.
     *
     * @param  int  $max_retries
     * @return $this
     */
    public function setMaxRetries(int $max_retries, string $policy = 'on-failure'): static
    {
        $this->restart['max_retries'] = $max_retries;
        $this->restart['policy'] = $policy;

        return $this;
    }

    /**
     * Set an environment variable.
     *
     * @param  string  $name The environment variable name
     * @param  string  $value The environment variable value
     * @return $this
     */
    public function setEnvironmentVariable(string $name, string $value): static
    {
        $this->env[$name] = $value;

        return $this;
    }

    /**
     * Set init cmd.
     *
     * @param  array  $args
     * @return $this
     */
    public function setInitCmd(array $args): static
    {
        $this->init['cmd'] = $args;

        return $this;
    }

    /**
     * Set the CPU count of the guest system.
     *
     * @param  int  $cpus The count of CPUs.
     * @return $this
     */
    public function setGuestCpus(int $cpus): static
    {
        $this->guest['cpus'] = $cpus;

        return $this;
    }

    /**
     * Set the CPU count of the guest system.
     *
     * @param  int  $memory_mb The memory in megabytes.
     * @return $this
     */
    public function setGuestMemory(int $memory_mb): static
    {
        $this->guest['memory_mb'] = $memory_mb;

        return $this;
    }

    /**
     * Set the Fly.io region.
     *
     * @param  string  $region
     * @return $this
     */
    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Build the configuration. Useful for creating a Machine.
     *
     * @return array
     */
    public function getConfig(): array
    {
        $config = [
            'config' => [
                'image' => $this->image,
                'restart' => $this->restart,
                'guest' => $this->guest,
            ],
        ];

        if (count($this->init) > 0) {
            $config['config']['init'] = $this->init;
        }

        if (count($this->env) > 0) {
            $config['config']['env'] = $this->env;
        }

        if (strlen($this->region) > 0) {
            $config['region'] = $this->region;
        }

        if (strlen($this->name) > 0) {
            $config['name'] = $this->name;
        }

        return $config;
    }
}
