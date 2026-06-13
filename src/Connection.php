<?php

namespace Dapodik\Laravel\API;

use Dapodik\Laravel\API\Concerns\Authentication;
use Dapodik\Laravel\API\Concerns\Authorization;
use Dapodik\Laravel\API\Concerns\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Arr;

class Connection
{
    use Authentication, Authorization, Configuration;

    protected Client $client;

    protected CookieJar $cookie;

    public function __construct(protected array $config)
    {
        $this->config = $this->parseConfig($config);
        $this->cookie = new CookieJar;
        $this->client = $this->setClient();
        $this->auth();
    }

    protected function auth(): void
    {
        match ($this->config['driver']) {
            'rest' => $this->authentication(),
            'webservice' => $this->authorization(),
            default => throw new \InvalidArgumentException("Driver {$this->config['driver']} not supported."),
        };
    }

    protected function parseConfig(array $config): array
    {
        $config = $this->parseDriver($this->parseHost($config));

        return match ($config['driver']) {
            'rest' => Arr::add(
                $this->parseUsername($this->parsePassword($this->parseKodeRegistrasi($config))),
                'path',
                '/rest'),
            'webservice' => Arr::add(
                $this->parseNpsn($this->parseToken($config)),
                'path',
                '/WebService'),
            default => throw new \InvalidArgumentException("Driver {$config['driver']} not supported."),
        };
    }

    protected function setClient(): Client
    {
        if (! isset($this->config['host'])) {
            throw new \InvalidArgumentException("Parameter 'host' not set.");
        }

        return new Client([
            'base_uri' => $this->config['host'],
            'cookies' => $this->cookie,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'DapodikOrg/DapodikAPI',
            ],
        ]);
    }
}
