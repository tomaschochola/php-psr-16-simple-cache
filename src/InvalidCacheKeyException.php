<?php

declare(strict_types=1);

namespace TomasChochola\Psr\SimpleCache;

use InvalidArgumentException;
use Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;

/**
 * @no-named-arguments
 */
final class InvalidCacheKeyException extends InvalidArgumentException implements PsrInvalidArgumentException
{
}
