<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

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
    #[NoDiscard()]
    public static function validate(mixed $key): string
    {
        if (!is_string($key) || $key === '' || strpbrk($key, '{}()/\\@:') !== false) {
            throw new InvalidCacheKeyException('$key');
        }

        return $key;
    }

    private function __construct()
    {
    }
}
