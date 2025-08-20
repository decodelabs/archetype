<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Resolver;
use DecodeLabs\Archetype\ResolverTrait;

class Archetype implements DefaultName
{
    use ResolverTrait;
    use DefaultNameTrait;

    public function getInterface(): string
    {
        return Resolver::class;
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function resolve(
        string $name
    ): ?string {
        $name = str_replace('/', '\\', $name);

        if (class_exists($interface = $name . 'Archetype')) {
            return $interface;
        }

        return Generic::class;
    }
}
