<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

interface DefaultResolver extends Resolver
{
    public function resolveDefault(): ?string;
}
