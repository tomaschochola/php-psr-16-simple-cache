<?php

declare(strict_types=1);

namespace TomasChochola\Psr\SimpleCache;

use NoDiscard;

use function is_string;
use function strpbrk;

/**
 * @no-named-arguments
 */
final class CacheKeys
{
    private function __construct()
    {
    }

    #[NoDiscard()]
    public static function validate(mixed $key): string
    {
        if (!is_string($key) || $key === '' || strpbrk($key, '{}()/\@:') !== false) {
            throw new InvalidCacheKeyException('$key');
        }

        return $key;
    }
}
