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

namespace Tests;

use DateInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException as PsrInvalidArgumentException;
use TomasChochola\Psr\SimpleCache\CacheKeys;
use TomasChochola\Psr\SimpleCache\InvalidCacheKeyException;
use TomasChochola\Psr\SimpleCache\NullSimpleCache;
use TomasChochola\Psr\SimpleCache\SimpleCaches;
use UnexpectedValueException;

use function iterator_to_array;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(CacheKeys::class)]
#[CoversClass(InvalidCacheKeyException::class)]
#[CoversClass(NullSimpleCache::class)]
#[CoversClass(SimpleCaches::class)]
#[Small()]
final class SimpleCacheTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function provideInvalidKeysCases(): iterable
    {
        yield 'empty' => [''];
        yield 'opening brace' => ['{'];
        yield 'closing brace' => ['}'];
        yield 'opening parenthesis' => ['('];
        yield 'closing parenthesis' => [')'];
        yield 'slash' => ['/'];
        yield 'backslash' => ['\\'];
        yield 'at sign' => ['@'];
        yield 'colon' => [':'];
    }

    #[Test()]
    public function nullCacheImplementsNoOpPsrBehavior(): void
    {
        $cache = new NullSimpleCache();
        $ttl = new DateInterval('PT1M');

        self::assertSame('default', $cache->get('key', 'default'));
        self::assertSame([], iterator_to_array($cache->getMultiple([])));
        self::assertSame(['only' => null], iterator_to_array($cache->getMultiple(['only'])));
        self::assertSame(['first' => 'default', 'second' => 'default'], iterator_to_array($cache->getMultiple(['first', 'second'], 'default')));
        self::assertFalse($cache->has('key'));
        self::assertTrue($cache->set('key', 'value', $ttl));
        self::assertTrue($cache->setMultiple(['1' => 'numeric key', 'key' => 'value'], 60));
        self::assertTrue($cache->delete('key'));
        self::assertTrue($cache->deleteMultiple(['key']));
        self::assertTrue($cache->clear());
    }

    #[DataProvider('provideInvalidKeysCases')]
    #[Test()]
    public function getRejectsEveryInvalidKey(string $key): void
    {
        $this->expectException(PsrInvalidArgumentException::class);

        (new NullSimpleCache())->get($key);
    }

    #[Test()]
    public function setRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        (new NullSimpleCache())->set('invalid:key', 'value');
    }

    #[Test()]
    public function deleteRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        (new NullSimpleCache())->delete('invalid:key');
    }

    #[Test()]
    public function hasRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        (new NullSimpleCache())->has('invalid:key');
    }

    #[Test()]
    public function getMultipleRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        iterator_to_array((new NullSimpleCache())->getMultiple(['invalid:key']));
    }

    #[Test()]
    public function setMultipleRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        (new NullSimpleCache())->setMultiple(['invalid:key' => 'value']);
    }

    #[Test()]
    public function deleteMultipleRejectsInvalidKey(): void
    {
        $this->expectException(InvalidCacheKeyException::class);

        (new NullSimpleCache())->deleteMultiple(['invalid:key']);
    }

    #[Test()]
    public function rememberRejectsFailedCacheWrite(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->with('key')->willReturn(false);
        $cache->expects($this->once())->method('set')->with('key', 'fresh')->willReturn(false)->seal();

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs($cache::class . '->set');

        (void) SimpleCaches::remember($cache, 'key', static fn(): string => 'fresh');
    }

    #[Test()]
    public function rememberReturnsExistingValueWithoutCallingFactory(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->with('key')->willReturn(true);
        $cache->expects($this->once())->method('get')->with('key')->willReturn('cached')->seal();

        self::assertSame('cached', SimpleCaches::remember($cache, 'key', static fn(): never => self::fail('Factory must not be called')));
    }

    #[Test()]
    public function rememberStoresAndReturnsFreshValueOnMiss(): void
    {
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('has')->with('key')->willReturn(false);
        $cache->expects($this->once())->method('set')->with('key', 'fresh')->willReturn(true)->seal();

        self::assertSame('fresh', SimpleCaches::remember($cache, 'key', static fn(): string => 'fresh'));
    }
}
