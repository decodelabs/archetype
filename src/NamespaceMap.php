<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

class NamespaceMap
{
    /**
     * @var array<string, NamespaceList>
     */
    protected array $namespaces = [];

    /**
     * @var array<string, array<string>>
     */
    protected array $aliases = [];

    /**
     * Add namespace
     *
     * @return $this
     */
    public function add(
        string $root,
        string $namespace,
        int $priority = 0
    ): static {
        if (!isset($this->namespaces[$root])) {
            $this->namespaces[$root] = new NamespaceList();
        }

        $this->namespaces[$root]->add($namespace, $priority);
        return $this;
    }

    /**
     * Has namespace?
     */
    public function has(
        string $root,
        string $namespace
    ): bool {
        return
            isset($this->namespaces[$root]) &&
            $this->namespaces[$root]->has($namespace);
    }

    /**
     * Remove namespace
     *
     * @return $this
     */
    public function remove(
        string $root,
        string $namespace
    ): static {
        if (isset($this->namespaces[$root])) {
            $this->namespaces[$root]->remove($namespace);
        }

        return $this;
    }

    /**
     * Add alias
     */
    public function addAlias(
        string $interface,
        string $alias
    ): void {
        if(!isset($this->aliases[$interface])) {
            $this->aliases[$interface] = [];
        }

        $this->aliases[$interface][$alias] = $alias;
    }

    /**
     * Has alias
     */
    public function hasAlias(
        string $interface,
        string $alias
    ): bool {
        return isset($this->aliases[$interface][$alias]);
    }

    /**
     * Remove alias
     */
    public function removeAlias(
        string $interface,
        string $alias
    ): void {
        unset($this->aliases[$interface][$alias]);
    }

    /**
     * Map namespace
     */
    public function map(
        string $namespace
    ): NamespaceList {
        $output = new NamespaceList();
        $this->applyMap($namespace, $output);

        foreach($this->aliases[$namespace] ?? [] as $alias) {
            $this->applyMap($alias, $output);
        }

        return $output;
    }

    protected function applyMap(
        string $namespace,
        NamespaceList $namespaces
    ): NamespaceList {
        $parts = explode('\\', $namespace);
        $inner = [];
        $namespaces->add($namespace, -1);

        while(!empty($parts)) {
            $root = implode('\\', $parts);

            if (isset($this->namespaces[$root])) {
                $mapTo = empty($inner) ? null : implode('\\', $inner);
                $namespaces->import($this->namespaces[$root], $mapTo);
            }

            array_unshift($inner, array_pop($parts));
        }

        return $namespaces;
    }
}
