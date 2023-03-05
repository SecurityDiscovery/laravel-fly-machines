<?php

namespace SecurityDiscovery\LaravelFlyMachines\Helpers;

class Machine
{
    /**
     * @var array An object defining the machine configuration. Options
     */
    protected array $config;

    /**
     * @var string|null Unique name for this machine. If omitted, one is generated for you.
     */
    protected ?string $name;

    /**
     * @var string|null The target region. Omitting this param launches in the same region as your WireGuard peer connection (somewhere near you).
     */
    protected ?string $region;

    /**
     * Create a new Machine helper using the only required field, the image.
     */
    public static function builder(): Machine
    {
        return new Machine();
    }

    /**
     * The Docker image to run
     *
     * @param string $image The image
     */
    public function image(string $image): static
    {
        $this->config['image'] = $image;

        return $this;
    }

    /**
     * Unique name for this machine. If omitted, one is generated for you.
     *
     * @param string $name Unique name for this machine
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set guest options.
     *
     * @param int|null $cpus Number of vCPUs (default 1)
     * @param int|null $memory_mb Memory in megabytes as multiples of 256 (default 256)
     * @param string|null $cpu_kind Memory in megabytes as multiples of 256 (default 256)
     * @param array|null $kernel_args Optional array of strings. Arguments passed to the kernel
     */
    public function guest(?int $cpus, ?int $memory_mb, ?string $cpu_kind, ?array $kernel_args): static
    {
        $this->config = $this->filter_array([
            'cpus' => $cpus,
            'memory_mb' => $memory_mb,
            'cpu_kind' => $cpu_kind,
            'kernel_args' => $kernel_args,
        ]);

        return $this;
    }

    /**
     * A named size for the VM, e.g. performance-2x or shared-cpu-2x.
     * Note: guest and size are mutually exclusive.
     *
     * @param string $size E.g. performance-2x or shared-cpu-2x
     */
    public function size(string $size): static
    {
        $this->config['size'] = $size;

        return $this;
    }

    /**
     * Set an environment variable.
     *
     * @param string $name The environment variable name
     * @param string $value The environment variable value
     */
    public function env(string $name, string $value): static
    {
        if (! array_key_exists('env', $this->config)) {
            $this->config['env'] = [];
        }

        $this->config['env'][$name] = $value;

        return $this;
    }

    /**
     * Defining a processes to run within a VM.
     * The Machine will stop if any process exits without error.
     *
     * @param string $name Process name
     * @param array|null $entrypoint An array of strings. The process that will run
     * @param array|null $cmd An array of strings. The arguments passed to the entrypoint
     * @param array|null $env An object filled with key/value pairs to be set as environment variables
     * @param string|null $user An optional user that the process runs under
     */
    public function process(string $name, ?array $entrypoint, ?array $cmd, ?array $env, ?string $user): static
    {
        if (! array_key_exists('processes', $this->config)) {
            $this->config['processes'] = [];
        }

        $this->config['processes'][] = $this->filter_array([
            'name' => $name,
            'entrypoint' => $entrypoint,
            'cmd' => $cmd,
            'env' => $env,
            'user' => $user,
        ]);

        return $this;
    }

    /**
     * Defining a processes to run within a VM.
     * The Machine will stop if any process exits without error.
     *
     * @param array|null $entrypoint An array of strings. The process that will run
     * @param array|null $exec An array of strings. The process that will run
     * @param array|null $cmd An array of strings. The arguments passed to the entrypoint
     * @param bool|null $tty TTY
     */
    public function init(?array $entrypoint, ?array $exec, ?array $cmd, ?bool $tty): static
    {
        $this->config['init'] = $this->filter_array([
            'entrypoint' => $entrypoint,
            'exec' => $exec,
            'cmd' => $cmd,
            'tty' => $tty,
        ]);

        return $this;
    }

    /**
     * Set the maximum retries of a machine.
     * MaxRetries is only relevant with the on-failure policy.
     *
     * @param int $max_retries
     * @param string $policy 'no' | 'on-failure' | 'always'
     * @return $this
     */
    public function retries(int $max_retries, string $policy = 'on-failure'): static
    {
        if (! array_key_exists('restart', $this->config)) {
            $this->config['restart'] = [];
        }

        $this->config['restart']['max_retries'] = $max_retries;
        $this->config['restart']['policy'] = $policy;

        return $this;
    }

    /**
     * Reference a previously created persistent volume
     *
     * @param string $volume_id The volume ID, visible in fly volumes list, i.e. vol_2n0l3vl60qpv635d
     * @param string $path Absolute path on the VM where the volume should be mounted. i.e. /data
     */
    public function mount(string $volume_id, string $path): static
    {
        if (! array_key_exists('mounts', $this->config)) {
            $this->config['mounts'] = [];
        }
        $this->config['mounts'][] = [
            'volume' => $volume_id,
            'path' => $path,
        ];

        return $this;
    }

    /**
     * Runs machine at the given interval. Interval starts at time of machine creation
     *
     * @param string $schedule Optionally one of hourly, daily, weekly, monthly.
     */
    public function schedule(string $schedule): static
    {
        $this->config['schedule'] = $schedule;

        return $this;
    }

    public function region(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Removes null values
     * @param array $arr
     * @return array
     */
    protected function filter_array(array $arr): array
    {
        return array_filter($arr, fn ($item) => !is_null($arr));
    }

    /**
     * Build the configuration. Useful for creating a Machine.
     */
    public function toArray(): array
    {
        $config = $this->filter_array([
            'name' => $this->name,
            'region' => $this->region,
        ]);
        $config['config'] = $this->config;

        return $config;
    }
}
