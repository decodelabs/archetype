<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

/**
 * @phpstan-require-implements DefaultName
 */
trait DefaultNameTrait
{
    /**
     * Resolve default Archetype class location
     */
    public function resolveDefault(): ?string
    {
        $parts = explode('\\', $this->getInterface());
        $name = array_pop($parts);

        foreach ($this->namespaces->map(implode('\\', $parts), false) as $namespace) {
            $class = $namespace . '\\' . $name;

            if (class_exists($class)) {
                return $class;
            }
        }

        $class = $this->resolve('Generic');

        if (
            $class !== null &&
            class_exists($class)
        ) {
            return $class;
        }

        return null;
    }
}
