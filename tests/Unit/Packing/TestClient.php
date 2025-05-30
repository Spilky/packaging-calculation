<?php declare(strict_types = 1);

namespace App\Tests\Unit\Packing;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

class TestClient implements ClientInterface
{

    private Response|null $response = null;

    private Throwable|null $exception = null;

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }

        if ($this->response === null) {
            throw new RuntimeException('No response set for TestClient.');
        }

        return $this->response;
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

}
