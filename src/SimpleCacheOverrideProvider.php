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
readonly class SimpleCacheOverrideProvider implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield NullSimpleCache::class => [NullSimpleCache::class, 'unload'];
        yield CacheInterface::class => [NullSimpleCache::class, 'unload'];
    }
}
