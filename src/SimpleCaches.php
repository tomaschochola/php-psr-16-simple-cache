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
use Psr\SimpleCache\CacheInterface;
use UnexpectedValueException;

/**
 * @no-named-arguments
 */
class SimpleCaches
{
    /**
     * @template T
     *
     * @param callable(): T $fresh
     *
     * @return T
     */
    #[NoDiscard]
    public static function remember(CacheInterface $cache, string $key, callable $fresh): mixed
    {
        if ($cache->has($key)) {
            return $cache->get($key);
        }

        $resolved = $fresh();
        $ok = $cache->set($key, $resolved);

        if (!$ok) {
            throw new UnexpectedValueException($cache::class . '->set');
        }

        return $resolved;
    }
}
