<?php

declare(strict_types=1);

namespace TomasChochola\Psr\SimpleCache;

use NoDiscard;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class ApcuSimpleCacheAssembler
{
    #[NoDiscard]
    public static function assemble(ContainerInterface $container): ApcuSimpleCache
    {
        return new ApcuSimpleCache();
    }
}
