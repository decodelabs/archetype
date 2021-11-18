<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

/**
 * global helpers
 */

namespace DecodeLabs\Archetype
{
    use DecodeLabs\Archetype;
    use DecodeLabs\Archetype\Resolver\Archetype as Resolver;
    use DecodeLabs\Veneer;

    // Register the Veneer facade
    Veneer::register(Handler::class, Archetype::class);

    // Load Archetype Resolver
    Archetype::register(new Resolver());
}
