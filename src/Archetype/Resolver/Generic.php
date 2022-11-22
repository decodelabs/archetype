<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Scanner;
use DecodeLabs\Archetype\ScannerTrait;
use Generator;

class Generic implements Scanner
{
    use ScannerTrait;

    /**
     * @phpstan-var class-string
     */
    protected string $interface;

    /**
     * @var array<string>
     */
    protected array $namespaces = [];


    /**
     * Init with interface
     *
     * @phpstan-param class-string $interface
     */
    public function __construct(string $interface)
    {
        $this->interface = $interface;
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
    public function addNamespace(string $namespace): void
    {
        $this->namespaces[] = $namespace;
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(string $name): ?string
    {
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
