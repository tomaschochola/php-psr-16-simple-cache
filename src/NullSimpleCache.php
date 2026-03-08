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

use DateInterval;
use Override;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * @no-named-arguments
 */
readonly class NullSimpleCache implements CacheInterface
{
    public static function unload(ContainerInterface $container): self
    {
        return new self();
    }

    #[Override]
    public function clear(): bool
    {
        return false;
    }

    #[Override]
    public function delete(string $key): bool
    {
        return false;
    }

    #[Override]
    public function deleteMultiple(iterable $keys): bool
    {
        return false;
    }

    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    #[Override]
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $key => $default;
        }
    }

    #[Override]
    public function has(string $key): bool
    {
        return false;
    }

    #[Override]
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        return false;
    }

    /**
     * @param iterable<mixed, mixed> $values
     */
    #[Override]
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        return false;
    }
}
