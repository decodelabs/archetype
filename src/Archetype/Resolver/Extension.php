<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Resolver;

class Extension implements Resolver
{
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
        return 10;
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(string $name): ?string
    {
        return $this->namespace . '\\' . $name;
    }
}
