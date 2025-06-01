<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use RuntimeException;
use Throwable;

class TestClient implements ClientInterface
{

    private Response|null $response = null;

    private Throwable|null $exception = null;

    /**
     * @phpstan-param array<string, mixed> $options
     * @phpstan-ignore-next-line Parameter contravariance is expected here
     */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->getResponse();
    }

    /**
     * @phpstan-param array<string, mixed> $options
     * @phpstan-ignore-next-line Parameter contravariance is expected here
     */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @param string|UriInterface $uri
     * @phpstan-param array<string, mixed> $options
     * @phpstan-ignore-next-line Parameter contravariance is expected here
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        return $this->getResponse();
    }

    /**
     * @param string|UriInterface $uri
     * @phpstan-param array<string, mixed> $options
     * @phpstan-ignore-next-line Parameter contravariance is expected here
     */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        throw new RuntimeException('Not implemented');
    }

    public function getConfig(?string $option = null): void
    {
        throw new RuntimeException('Not implemented');
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
        $this->exception = null;
    }

    public function throwException(Throwable $exception): void
    {
        $this->exception = $exception;
        $this->response = null;
    }

    private function getResponse(): ResponseInterface
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }

        if ($this->response === null) {
            throw new RuntimeException('No response set for TestClient.');
        }

        return $this->response;
    }

}
