<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

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


    /**
     * Init with interface
     *
     * @param class-string $interface
     */
    public function __construct(
        string $interface
    ) {
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
        foreach ($this->namespaces->map($this->interface) as $namespace) {
            yield from $this->scanNamespaceClasses($namespace, $this->interface);
        }
    }
}
