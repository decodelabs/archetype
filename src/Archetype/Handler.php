<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use DecodeLabs\Exceptional;
use Generator;

class Handler
{
    /**
     * @var array<class-string, array<Resolver>>
     */
    protected array $resolvers = [];

    /**
     * Register resolver
     */
    public function register(Resolver $resolver): void
    {
        /** @var string $interface */
        $interface = $resolver->getInterface();

        if (
            !interface_exists($interface) &&
            !class_exists($interface)
        ) {
            throw Exceptional::NotFound('Interface ' . $interface . ' does not exist');
        }

        if ($interface !== Resolver::class) {
            $this->ensureResolver($interface);
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
     * @template T
     * @phpstan-param class-string<T> $interface
     * @phpstan-return class-string<T>
     */
    public function resolve(
        string $interface,
        string $name
    ): string {
        // Name is a classname already
        if (
            class_exists($name) &&
            is_subclass_of($name, $interface)
        ) {
            /** @phpstan-var class-string<T> $name */
            return $name;
        }


        // Make sure there's at least one resolver for interface
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (null !== ($class = $resolver->resolve($name))) {
                if (!class_exists($class)) {
                    continue;
                }

                if (!is_subclass_of($class, $interface)) {
                    throw Exceptional::UnexpectedValue('Class ' . $class . ' does not implement ' . $interface);
                }

                /** @phpstan-var class-string<T> */
                return $class;
            }
        }

        throw Exceptional::NotFound('Could not resolve "' . $name . '" for interface ' . $interface);
    }


    /**
     * Find file in space
     *
     * @phpstan-param class-string $interface
     */
    public function findFile(
        string $interface,
        string $name
    ): string {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (!$resolver instanceof Finder) {
                continue;
            }

            if (null !== ($path = $resolver->findFile($name))) {
                if (!is_file($path)) {
                    continue;
                }

                return $path;
            }
        }

        throw Exceptional::NotFound('Could not find file "' . $name . '" in namespace ' . $interface);
    }


    /**
     * Scan Resolvers for available classes
     *
     * @phpstan-param class-string $interface
     * @return Generator<string, string>
     * @phpstan-return Generator<string, class-string>
     */
    public function scanClasses(
        string $interface
    ): Generator {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (!$resolver instanceof Scanner) {
                continue;
            }

            yield from $resolver->scanClasses();
        }
    }



    /**
     * Ensure resolver is available
     *
     * @phpstan-param class-string $interface
     */
    protected function ensureResolver(string $interface): void
    {
        if (!isset($this->resolvers[$interface])) {
            if (!$class = $this->resolve(Resolver::class, $interface)) {
                throw Exceptional::NotFound('Interface ' . $interface . ' has no Archetype resolver');
            }

            /** @phpstan-var class-string<Resolver> $class */
            $this->resolvers[$interface][] = new $class($interface);
        }
    }
}
