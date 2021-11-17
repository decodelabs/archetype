<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use DecodeLabs\Exceptional;

class Handler
{
    /**
     * @var array<class-string, array<Resolver>>
     */
    protected $resolvers = [];

    /**
     * Register resolver
     */
    public function register(Resolver $resolver): void
    {
        $interface = $resolver->getInterface();

        if (
            !interface_exists($interface) &&
            !class_exists($interface)
        ) {
            throw Exceptional::NotFound('Interface ' . $interface . ' does not exist');
        }

        $reorder = !empty($this->resolvers[$interface] ?? null);
        $this->resolvers[$interface][] = $resolver;

        if ($reorder) {
            usort($this->resolvers[$interface], function ($a, $b) {
                return $a->getPriority() <=> $b->getPriority();
            });
        }
    }

    /**
     * Unregister resolver
     */
    public function unregister(Resolver $resolver): void
    {
        $interface = $resolver->getInterface();

        foreach ($this->resolvers[$interface] ?? [] as $key => $registered) {
            if ($registered === $resolver) {
                unset($this->resolvers[$interface][$key]);
                break;
            }
        }
    }


    /**
     * Resolve archetype class
     *
     * @param class-string $interface
     * @return class-string
     */
    public function resolve(string $interface, string $name): string
    {
        if (!isset($this->resolvers[$interface])) {
            if (!$class = $this->resolve(Resolver::class, $interface)) {
                throw Exceptional::NotFound('Interface ' . $interface . ' has no Archetype resolver');
            }

            $this->resolvers[$interface][] = new $class($interface);
        }

        foreach ($this->resolvers[$interface] as $resolver) {
            if ($class = $resolver->resolve($name)) {
                if (!class_exists($class)) {
                    continue;
                }

                if (!is_subclass_of($class, $interface)) {
                    throw Exceptional::UnexpectedValue('Class ' . $class . ' does not implement ' . $interface);
                }

                return $class;
            }
        }

        throw Exceptional::NotFound('Could not resolve "' . $name . '" for interface ' . $interface);
    }
}
