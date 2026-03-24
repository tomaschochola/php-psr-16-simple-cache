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
use NoDiscard;
use Override;
use Psr\SimpleCache\CacheInterface;
use UnexpectedValueException;

use function array_key_exists;
use function assert;
use function file_exists;
use function file_put_contents;
use function is_array;
use function is_int;
use function is_string;
use function mb_strlen;
use function unlink;
use function var_export;

/**
 * @no-named-arguments
 */
readonly class ExportSimpleCache implements CacheInterface
{
    public readonly string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    #[NoDiscard]
    #[Override]
    public function clear(): bool
    {
        if (!file_exists($this->filename)) {
            return true;
        }

        return unlink($this->filename);
    }

    #[NoDiscard]
    #[Override]
    public function delete(string $key): bool
    {
        $registry = $this->read();

        if (!array_key_exists($key, $registry)) {
            return true;
        }

        unset($registry[$key]);

        $this->write($registry);

        return true;
    }

    #[NoDiscard]
    #[Override]
    public function deleteMultiple(iterable $keys): bool
    {
        $registry = $this->read();

        foreach ($keys as $key) {
            unset($registry[(string) $key]);
        }

        $this->write($registry);

        return true;
    }

    #[NoDiscard]
    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        $registry = $this->read();

        if (!array_key_exists($key, $registry)) {
            return $default;
        }

        return $registry[$key];
    }

    #[NoDiscard]
    #[Override]
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $registry = $this->read();

        foreach ($keys as $key) {
            $key = (string) $key;

            if (!array_key_exists($key, $registry)) {
                yield $key => $default;
            } else {
                yield $key => $registry[$key];
            }
        }
    }

    #[NoDiscard]
    #[Override]
    public function has(string $key): bool
    {
        $registry = $this->read();

        return array_key_exists($key, $registry);
    }

    #[NoDiscard]
    #[Override]
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        $registry = $this->read();
        $registry[$key] = $value;

        $this->write($registry);

        return true;
    }

    /**
     * @param iterable<mixed, mixed> $values
     */
    #[NoDiscard]
    #[Override]
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        $registry = $this->read();

        foreach ($values as $key => $value) {
            assert(is_string($key) || is_int($key));

            $registry[(string) $key] = $value;
        }

        $this->write($registry);

        return true;
    }

    /**
     * @return array<mixed, mixed>
     */
    #[NoDiscard]
    private function read(): array
    {
        if (!file_exists($this->filename)) {
            return [];
        }

        $registry = require $this->filename;

        if (!is_array($registry)) {
            throw new UnexpectedValueException('require');
        }

        return $registry;
    }

    /**
     * @param array<mixed, mixed> $registry
     */
    private function write(array $registry): void
    {
        $data = "<?php\n\ndeclare(strict_types=1);\n\nreturn " . var_export($registry, true) . ";\n";
        $size = file_put_contents($this->filename, $data);

        if ($size !== mb_strlen($data, '8bit')) {
            throw new UnexpectedValueException('file_put_contents');
        }
    }
}
