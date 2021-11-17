<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Resolver;

class Local implements Resolver
{
    /**
     * @var class-string
     */
    protected $interface;


    /**
     * Init with interface
     *
     * @param class-string $interface
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
        return 5;
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(string $name): ?string
    {
        return $this->interface . '\\' . $name;
    }
}
