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

class Extension implements Scanner
{
    use ScannerTrait;

    /**
     * @phpstan-var class-string
     */
    protected string $interface;
    protected string $namespace;


    /**
     * Init with interface
     *
     * @phpstan-param class-string $interface
     */
    public function __construct(
        string $interface,
        string $namespace
    ) {
        $this->interface = $interface;
        $this->namespace = $namespace;
    }

    /**
     * Get mapped interface
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * Get namespace
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * Get resolver priority
     */
    public function getPriority(): int
    {
        return 15;
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(string $name): ?string
    {
        $name = str_replace('/', '\\', $name);
        $name = trim($name, '\\');

        return $this->namespace . '\\' . $name;
    }

    /**
     * Scan for available for classes
     */
    public function scanClasses(): Generator
    {
        yield from $this->scanNamespaceClasses($this->namespace);
    }
}
