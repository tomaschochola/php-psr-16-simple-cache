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
readonly class SimpleCacheProvider implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ApcuSimpleCache::class => [ApcuSimpleCache::class, 'unload'];
        yield CacheInterface::class => [ApcuSimpleCache::class, 'unload'];
    }
}
