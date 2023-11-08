<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\DefaultResolver;
use DecodeLabs\Archetype\DefaultResolverTrait;
use DecodeLabs\Archetype\NamespaceMap;
use DecodeLabs\Archetype\Scanner;
use DecodeLabs\Archetype\ScannerTrait;
use Generator;

class Generic implements Scanner, DefaultResolver
{
    use ScannerTrait;
    use DefaultResolverTrait;

    /**
     * @var class-string
     */
    protected string $interface;

    protected NamespaceMap $namespaces;


    /**
     * Init with interface
     *
     * @param class-string $interface
     */
    public function __construct(
        string $interface
    ) {
        $this->interface = $interface;
        $this->namespaces = new NamespaceMap();
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
        $this->namespaces->add($namespace, $priority);
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(
        string $name
    ): ?string {
        $name = str_replace('/', '\\', $name);
        $name = trim($name, '\\');

        $classes = [
            $this->interface . '\\' . $name
        ];

        foreach ($this->namespaces as $namespace) {
            $classes[] = $namespace . '\\' . $name;
        }

        foreach (array_reverse($classes) as $class) {
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

        foreach ($this->namespaces as $namespace) {
            yield from $this->scanNamespaceClasses($namespace, $this->interface);
        }
    }
}
