<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

trait DefaultResolverTrait
{
    /**
     * Resolve default Archetype class location
     */
    public function resolveDefault(): ?string
    {
        return $this->resolve('Generic');
    }
}
