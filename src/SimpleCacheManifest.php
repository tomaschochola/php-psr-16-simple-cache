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
readonly class SimpleCacheManifest implements IteratorAggregate
{
    #[NoDiscard]
    #[Override]
    public function getIterator(): Traversable
    {
        yield ApcuSimpleCache::class => [ApcuSimpleCacheAssembler::class, 'assemble'];
        yield CacheInterface::class => [ApcuSimpleCacheAssembler::class, 'assemble'];
        yield NullSimpleCache::class => [NullSimpleCacheAssembler::class, 'assemble'];
    }
}
