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
     * @var array<string, NamespaceList>
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
     *
     * @return $this
     */
    public function addAlias(
        string $interface,
        string $alias,
        int $priority = 0
    ): static {
        if (!isset($this->aliases[$interface])) {
            $this->aliases[$interface] = new NamespaceList();
        }

        $this->aliases[$interface]->add($alias, $priority);
        return $this;
    }

    /**
     * Has alias
     */
    public function hasAlias(
        string $interface,
        string $alias
    ): bool {
        return
            isset($this->aliases[$interface]) &&
            $this->aliases[$interface]->has($alias);
    }

    /**
     * Remove alias
     *
     * @return $this
     */
    public function removeAlias(
        string $interface,
        string $alias
    ): static {
        if (isset($this->aliases[$interface])) {
            $this->aliases[$interface]->remove($alias);
        }

        return $this;
    }

    /**
     * Map namespace
     */
    public function map(
        string $namespace,
        bool $includeRoot = true
    ): NamespaceList {
        $output = new NamespaceList();
        $this->applyMap($namespace, $output, -1, $includeRoot);
        return $output;
    }

    protected function applyMap(
        string $namespace,
        NamespaceList $namespaces,
        int $priority = 0,
        bool $includeRoot = true
    ): NamespaceList {
        // Import root
        if ($includeRoot) {
            $namespaces->add($namespace, $priority);
        }

        $parts = explode('\\', $namespace);
        $inner = [];

        while (!empty($parts)) {
            $root = implode('\\', $parts);

            // Import root maps
            if (isset($this->namespaces[$root])) {
                $mapTo = empty($inner) ? null : implode('\\', $inner);
                $namespaces->import($this->namespaces[$root], $mapTo, $namespace);
            }


            // Aliases
            $wild = false;
            $key = null;

            if (
                isset($this->aliases[$root . '\\*']) &&
                // Wildcards only make sense for one level
                count($inner) <= 1
            ) {
                $key = $root . '\\*';
                $wild = true;
            } elseif (isset($this->aliases[$root])) {
                $key = $root;
            }

            if ($key !== null) {
                foreach ($this->aliases[$key] ?? [] as $priority => $alias) {
                    $append = $inner;

                    if ($wild) {
                        array_pop($append);
                    }

                    if (!empty($append)) {
                        $alias .= '\\' . implode('\\', $append);
                    }

                    $this->applyMap($alias, $namespaces, $priority);
                }

                return $namespaces;
            }

            // Shift parts
            array_unshift($inner, array_pop($parts));
        }

        return $namespaces;
    }
}
