<?php declare(strict_types = 1);

namespace App\DataStructure;

use JsonException;
use function get_debug_type;
use function is_array;
use function json_decode;
use function sprintf;
use const JSON_BIGINT_AS_STRING;
use const JSON_THROW_ON_ERROR;

class Json
{

    /**
     * @return array<int|string, mixed>
     * @throws JsonException
     */
    public static function decode(string $content): array
    {
        if ($content === '') {
            throw new JsonException('Content is empty.');
        }

        $decoded = json_decode($content, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);

        if (!is_array($decoded)) {
            throw new JsonException(sprintf('JSON content was expected to decode to an array, "%s" returned.', get_debug_type($decoded)));
        }

        return $decoded;
    }

}
