<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use DecodeLabs\Archetype\Resolver;
use Generator;

interface Scanner extends Resolver
{
    /**
     * @return Generator<string, class-string>
     */
    public function scanClasses(): Generator;
}
