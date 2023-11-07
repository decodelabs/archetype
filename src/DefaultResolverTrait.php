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
        $parts = explode('\\', $this->getInterface());
        $name = array_pop($parts);

        foreach ([$name, 'Generic'] as $rName) {
            $class = $this->resolve($rName);

            if (
                $class !== null &&
                class_exists($class)
            ) {
                return $class;
            }
        }

        return null;
    }
}
