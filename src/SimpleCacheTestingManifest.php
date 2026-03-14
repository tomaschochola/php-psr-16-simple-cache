<?php

declare(strict_types=1);

namespace TomasChochola\Psr\SimpleCache;

use IteratorAggregate;
use NoDiscard;
use Override;
use Psr\SimpleCache\CacheInterface;
use Traversable;

/**
 * @no-named-arguments
 *
 * @implements IteratorAggregate<mixed, mixed>
 */
readonly class SimpleCacheTestingManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        $cache = new NullSimpleCache();

        yield NullSimpleCache::class => $cache;

        yield CacheInterface::class => $cache;
    }
}
