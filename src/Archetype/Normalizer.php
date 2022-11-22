<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

interface Normalizer
{
    /**
     * @phpstan-return class-string
     */
    public function getInterface(): string;

    public function getPriority(): int;

    public function normalize(string $name): ?string;
}
