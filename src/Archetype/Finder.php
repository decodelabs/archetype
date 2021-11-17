<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

interface Finder extends Resolver
{
    public function findFile(string $name): ?string;
}
