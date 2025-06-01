<?php declare(strict_types = 1);

namespace App\Http;

use GuzzleHttp\Psr7\Response;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use function json_encode;
use const JSON_THROW_ON_ERROR;

class JsonResponse
{

    /**
     * @param array<string|int, mixed> $body
     * @throws JsonException
     */
    public static function createOk(array $body): ResponseInterface
    {
        return self::create(200, $body);
    }

    /**
     * @throws JsonException
     */
    public static function createError(string $error): ResponseInterface
    {
        return self::create(400, ['error' => $error]);
    }

    /**
     * @param array<string|int, mixed> $body
     * @throws JsonException
     */
    private static function create(int $code, array $body): ResponseInterface
    {
        return new Response(
            $code,
            ['Content-Type' => 'application/json'],
            json_encode($body, JSON_THROW_ON_ERROR),
        );
    }

}
