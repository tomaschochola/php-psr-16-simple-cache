<?php

declare(strict_types=1);

namespace TomasChochola\Psr\SimpleCache;

use NoDiscard;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class NullSimpleCacheAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): NullSimpleCache
    {
        return new NullSimpleCache();
    }
}
