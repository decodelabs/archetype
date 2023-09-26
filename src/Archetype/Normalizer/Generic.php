<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Normalizer;

use Closure;
use DecodeLabs\Archetype\Normalizer;

class Generic implements Normalizer
{
    /**
     * @var class-string
     */
    protected string $interface;

    protected int $priority = 10;
    protected Closure $normalizer;

    /**
     * Init with callable
     *
     * @param class-string $interface
     */
    public function __construct(
        string $interface,
        callable $normalizer,
        int $priority = 10
    ) {
        $this->interface = $interface;
        $this->normalizer = Closure::fromCallable($normalizer);
        $this->priority = $priority;
    }

    /**
     * Get mapped interface
     */
    public function getInterface(): string
    {
        return $this->interface;
    }

    /**
     * Get normalizer priority
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Call closure
     */
    public function normalize(string $name): ?string
    {
        return ($this->normalizer)($name, $this->interface);
    }
}
