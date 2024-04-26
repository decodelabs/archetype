<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

trait ResolverTrait
{
    protected NamespaceMap $namespaces;

    public function setNamespaceMap(
        NamespaceMap $map
    ): void {
        $this->namespaces = $map;
    }

    public function getNamespaceMap(): NamespaceMap
    {
        if (!isset($this->namespaces)) {
            $this->namespaces = new NamespaceMap();
        }

        return $this->namespaces;
    }
}
