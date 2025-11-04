<?php

/**
 * Archetype
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Resolver;

interface FileFinder extends Resolver
{
    public function findFile(
        string $name
    ): ?string;
}
