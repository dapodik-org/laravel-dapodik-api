<?php

namespace Dapodik\Laravel\API;

use Dapodik\Laravel\API\Contracts\ResponseContract;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseContract
{
    protected string $reasonPhrase;

    protected int $statusCode;

    protected array $headers;

    protected string $protocol;

    protected ?StreamInterface $stream;

    public function __construct(ResponseInterface $response)
    {
        $this->reasonPhrase = $response->getReasonPhrase();
        $this->statusCode = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->protocol = $response->getProtocolVersion();
        $this->stream = $response->getBody();
    }

    public function content(): array
    {
        $content = self::__toString();

        if (! is_null($error = preg_match("/\{.*?['\"]success['\"]:.*?false,(.*?)}/", $content, $match) ? $match[0] : null)) {
            throw new \InvalidArgumentException(json_decode($error)->message, json_decode($error)->http_code);
        }

        return (array) json_decode($content, true);
    }

    public function toArray(): array
    {
        return $this->content();
    }

    public function toCollection(): Collection
    {
        return new Collection($this->content());
    }

    public function toJson(int $flags = 0, int $depth = 512): false|string
    {
        return json_encode($this->content(), $flags, $depth);
    }

    public function __toString(): string
    {
        return $this->stream->getContents();
    }
}
