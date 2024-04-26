<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\NamespaceList;
use DecodeLabs\Archetype\ResolverTrait;
use Generator;

class Generic implements Scanner, DefaultName
{
    use ResolverTrait;
    use ScannerTrait;
    use DefaultNameTrait;

    /**
     * @var class-string
     */
    protected string $interface;

    protected NamespaceList $namespaceList;


    /**
     * Init with interface
     *
     * @param class-string $interface
     */
    public function __construct(
        string $interface
    ) {
        $this->interface = $interface;
        $this->namespaceList = new NamespaceList();
    }

    /**
     * Get mapped interface
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * Get resolver priority
     */
    public function getPriority(): int
    {
        return 20;
    }

    /**
     * Add namespace
     */
    public function addNamespace(
        string $namespace,
        int $priority = 0
    ): void {
        $this->namespaceList->add($namespace, $priority);
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(
        string $name
    ): ?string {
        $name = str_replace('/', '\\', $name);
        $name = trim($name, '\\');

        foreach ($this->namespaces->map($this->interface) as $namespace) {
            $class = $namespace . '\\' . $name;

            if (class_exists($class)) {
                return $class;
            }
        }

        return null;
    }


    /**
     * Scan for available for classes
     */
    public function scanClasses(): Generator
    {
        yield from $this->scanNamespaceClasses($this->interface);

        foreach ($this->namespaceList as $namespace) {
            yield from $this->scanNamespaceClasses($namespace, $this->interface);
        }
    }
}
