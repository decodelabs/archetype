<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use Countable;
use DecodeLabs\Nuance\Dumpable;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;
use Generator;
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
     * @var array<string,int>
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
     * List contains namespace or class
     */
    public function contains(
        string $namespace,
    ): bool {
        if (isset($this->namespaces[$namespace])) {
            return true;
        }

        foreach ($this->namespaces as $ns => $priority) {
            if (str_starts_with($namespace, $ns . '\\')) {
                return true;
            }
        }

        return false;
    }


    /**
     * Remove namespace prefix from class
     */
    public function localize(
        string $namespace
    ): ?string {
        if (isset($this->namespaces[$namespace])) {
            return '';
        }

        foreach ($this->namespaces as $ns => $priority) {
            if (str_starts_with($namespace, $ns . '\\')) {
                return substr($namespace, strlen($ns) + 1);
            }
        }

        return null;
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
     * @return Generator<int, string>
     */
    public function getIterator(): Generator
    {
        uasort($this->namespaces, function ($a, $b) {
            return $a <=> $b;
        });

        foreach (array_reverse($this->namespaces) as $namespace => $priority) {
            yield $priority => $namespace;
        }
    }

    /**
     * Count namespaces
     */
    public function count(): int
    {
        return count($this->namespaces);
    }

    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->values = $this->namespaces;
        return $entity;
    }
}
