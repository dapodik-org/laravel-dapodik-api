<?php

namespace Dapodik\Laravel\API\Concerns;

use Dapodik\Laravel\API\Contracts\ResponseContract;
use Dapodik\Laravel\API\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

trait Request
{
    protected function setHeaders(string $key, $value): void
    {
        $this->config['options']['headers'][$key] = $value;
    }

    protected function getHeaders(?string $key = null)
    {
        if (! is_null($key)) {
            return $this->config['options']['headers'][$key];
        }

        return $this->config['options']['headers'];
    }

    protected function setQuery(string $key, $value): void
    {
        $this->config['options']['query'][$key] = $value;
    }

    protected function getQuery(?string $key = null)
    {
        if (! is_null($key)) {
            return $this->config['options']['query'][$key];
        }

        return $this->config['options']['query'];
    }

    protected function setFormParams(string $key, $value): void
    {
        $this->config['options']['form_params'][$key] = $value;
    }

    protected function getFormParams(?string $key = null)
    {
        if (! is_null($key)) {
            return $this->config['options']['form_params'][$key];
        }

        return $this->config['options']['form_params'];
    }

    protected function forgeOptions(string $options): void
    {
        unset($this->config['options'][$options]);
    }

    protected function _request(string $method, $uri, array $options = []): ResponseInterface
    {
        $options = array_merge($options, $this->config['options']);

        try {
            return $this->client->request($method, $uri, $options);
        } catch (ConnectException|GuzzleException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode());
        }
    }

    protected function loginPage(): ?string
    {
        try {
            $page = $this->_request('GET', '/')->getBody()->getContents();
        } catch (\InvalidArgumentException $e) {
            $page = null;
        }

        return $page;
    }

    /**
     * @param  string|null  $find  key|value
     */
    protected function getSemester(?string $find = null): array|string
    {
        $regex = "/name=semester_id.*?option.+value=['\"](\d+)['\"].+selected.*?>(.*?)<\/option>/";

        $semesters = preg_match($regex, $this->loginPage(), $match) ? [$match[1], $match[2]] : [];

        return match ($find) {
            'key' => $semesters[0],
            'value' => $semesters[1],
            default => $semesters,
        };
    }

    public function isConnect(): bool
    {
        return ! is_null($this->loginPage());
    }

    protected function getPath(): string
    {
        return $this->config['path'];
    }

    public function request(string $method, $uri, array $options = []): ResponseContract
    {
        $uri = Str::start($this->getPath(), '/').Str::start($uri, '/');

        return new Response($this->_request($method, $uri, $options));
    }

    public function query($uri, array $where = [], string $method = 'GET'): ResponseContract
    {
        if ($this->config['driver'] == 'rest' && empty($where)) {
            throw new \InvalidArgumentException('Query is required.');
        }
        foreach ($where as $key => $value) {
            $this->setQuery($key, $value);
        }

        return $this->request($method, $uri);
    }
}
