<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use ArrayIterator;
use Countable;
use DecodeLabs\Glitch\Dumpable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<string>
 */
class NamespaceList implements
    Countable,
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
     * Import another list
     */
    public function import(
        NamespaceList $list,
        ?string $mapTo = null,
        ?string $filter = null
    ): void {
        foreach ($list->namespaces as $namespace => $priority) {
            if (
                $filter !== null &&
                str_starts_with($filter, $namespace)
            ) {
                continue;
            }

            if ($mapTo !== null) {
                $namespace .= '\\' . $mapTo;
            }

            $this->add($namespace, $priority);
        }
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

        return new ArrayIterator(array_reverse(array_keys($this->namespaces)));
    }

    /**
     * Count namespaces
     */
    public function count(): int
    {
        return count($this->namespaces);
    }

    /**
     * Dump for glitch
     */
    public function glitchDump(): iterable
    {
        yield 'values' => $this->namespaces;
    }
}
