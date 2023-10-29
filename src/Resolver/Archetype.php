<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\DefaultResolver;
use DecodeLabs\Archetype\DefaultResolverTrait;
use DecodeLabs\Archetype\Resolver;

class Archetype implements DefaultResolver
{
    use DefaultResolverTrait;

    /**
     * Get mapped interface
     */
    public function getInterface(): string
    {
        return Resolver::class;
    }

    /**
     * Get resolver priority
     */
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * Resolve Archetype class location
     */
    public function resolve(string $name): ?string
    {
        $name = str_replace('/', '\\', $name);

        if (class_exists($interface = $name . 'Archetype')) {
            return $interface;
        }

        return Generic::class;
    }
}
