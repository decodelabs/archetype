<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use ArrayIterator;
use DecodeLabs\Glitch\Dumpable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<string>
 */
class NamespaceMap implements
    IteratorAggregate,
    Dumpable
{
    /**
     * @var array<string, int>
     */
    protected array $namespaces = [];

    /**
     * Add namespace
     *
     * @return $this
     */
    public function add(
        string $namespace,
        int $priority = 0
    ): static {
        $this->namespaces[$namespace] = $priority;
        return $this;
    }

    /**
     * Has namespace?
     */
    public function has(
        string $namespace
    ): bool {
        return isset($this->namespaces[$namespace]);
    }

    /**
     * Remove namespace
     *
     * @return $this
     */
    public function remove(
        string $namespace
    ): static {
        unset($this->namespaces[$namespace]);
        return $this;
    }

    /**
     * Get iterator
     *
     * @return ArrayIterator<int, string>
     */
    public function getIterator(): ArrayIterator
    {
        uasort($this->namespaces, function ($a, $b) {
            return $a <=> $b;
        });

        return new ArrayIterator(array_keys($this->namespaces));
    }

    /**
     * Dump for glitch
     */
    public function glitchDump(): iterable
    {
        yield 'values' => $this->namespaces;
    }
}
