<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype;

use DecodeLabs\Archetype\Normalizer\Generic as GenericNormalizer;
use DecodeLabs\Archetype\Resolver\Generic as GenericResolver;
use DecodeLabs\Exceptional;
use Generator;
use ReflectionClass;

class Handler
{
    /**
     * @var array<class-string, array<Resolver>>
     */
    protected array $resolvers = [];

    /**
     * @var array<class-string, array<Normalizer>>
     */
    protected array $normalizers = [];

    /**
     * Register resolver or pre-processor
     */
    public function register(
        Resolver|Normalizer $item
    ): void {
        /** @var string $interface */
        $interface = $item->getInterface();

        if (
            !interface_exists($interface) &&
            !class_exists($interface)
        ) {
            throw Exceptional::NotFound('Interface ' . $interface . ' does not exist');
        }

        if ($interface !== Resolver::class) {
            $this->ensureResolver($interface);
        }

        $key = $this->getListKey($item);

        $reorder = !empty($this->{$key}[$interface] ?? null);
        $this->{$key}[$interface][] = $item;

        if ($reorder) {
            usort($this->{$key}[$interface], function ($a, $b) {
                return $a->getPriority() <=> $b->getPriority();
            });
        }
    }

    /**
     * Unregister resolver
     */
    public function unregister(
        Resolver|Normalizer $item
    ): void {
        $interface = $item->getInterface();
        $key = $this->getListKey($item);

        foreach ($this->{$key}[$interface] ?? [] as $key => $registered) {
            if ($registered === $item) {
                unset($this->{$key}[$interface][$key]);
                break;
            }
        }
    }


    /**
     * Register custom normalizer
     *
     * @template T
     * @param class-string<T> $interface
     */
    public function registerCustomNormalizer(
        string $interface,
        callable $normalizer,
        int $priority = 10
    ): Normalizer {
        $normalizer = new GenericNormalizer(
            $interface,
            $normalizer,
            $priority
        );

        $this->register($normalizer);
        return $normalizer;
    }

    protected function getListKey(
        Resolver|Normalizer $item
    ): string {
        if ($item instanceof Resolver) {
            return 'resolvers';
        } else {
            return 'normalizers';
        }
    }


    /**
     * Add namespace to Generic resolver
     *
     * @template T
     * @param class-string<T> $interface
     */
    public function extend(
        string $interface,
        string $namespace
    ): void {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if ($resolver instanceof GenericResolver) {
                $resolver->addNamespace($namespace);
                return;
            }
        }

        throw Exceptional::NotFound('Interface ' . $interface . ' does not have a local resolver');
    }


    /**
     * Resolve archetype class
     *
     * @template T of object
     * @param class-string<T> $interface
     * @param class-string<T>|callable(class-string<T>): class-string<T>|null $default
     * @return class-string<T>
     */
    public function resolve(
        string $interface,
        string $name,
        string|callable|null $default = null
    ): string {
        // Name is a classname already
        if (
            class_exists($name) &&
            $this->isResolved($interface, $name)
        ) {
            /** @var class-string<T> $name */
            return $name;
        }


        // Make sure there's at least one resolver for interface
        $this->ensureResolver($interface);
        $name = $this->normalize($interface, $name);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (null !== ($class = $resolver->resolve($name))) {
                if (!class_exists($class)) {
                    continue;
                }

                return $this->checkResolution($interface, $class);
            }
        }


        // Default
        if (is_callable($default)) {
            $default = (string)$default($interface);
        }

        if ($default !== null) {
            return $this->checkResolution($interface, $default);
        }

        throw Exceptional::NotFound('Could not resolve "' . $name . '" for interface ' . $interface);
    }

    /**
     * @template T of object
     * @param class-string<T> $interface
     */
    protected function isResolved(
        string $interface,
        string $class
    ): bool {
        return
            is_subclass_of($class, $interface) ||
            (
                $class === $interface &&
                (new ReflectionClass($class))->isInstantiable()
            );
    }

    /**
     * @template T of object
     * @param class-string<T> $interface
     * @return class-string<T>
     */
    protected function checkResolution(
        string $interface,
        string $class
    ): string {
        if ($this->isResolved($interface, $class)) {
            /** @var class-string<T> $class */
            return $class;
        }

        if ($class === $interface) {
            $message = 'Class ' . $class . ' is not instantiable';
        } else {
            $message = 'Class ' . $class . ' does not implement ' . $interface;
        }

        throw Exceptional::UnexpectedValue($message);
    }

    /**
     * Normalize input name
     *
     * @template T of object
     * @param class-string<T> $interface
     */
    public function normalize(
        string $interface,
        string $name
    ): string {
        foreach ($this->normalizers[$interface] ?? [] as $proc) {
            if (null !== ($newName = $proc->normalize($name))) {
                return $newName;
            }
        }

        return $name;
    }


    /**
     * Find file in space
     *
     * @param class-string $interface
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
     * @param class-string $interface
     * @return Generator<string, class-string>
     */
    public function scanClasses(
        string $interface
    ): Generator {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (!$resolver instanceof Scanner) {
                continue;
            }

            foreach ($resolver->scanClasses() as $path => $class) {
                $ref = new ReflectionClass($class);

                if (!$ref->implementsInterface($interface)) {
                    continue;
                }

                yield $path => $class;
            }
        }
    }



    /**
     * Ensure resolver is available
     *
     * @param class-string $interface
     */
    protected function ensureResolver(string $interface): void
    {
        if (!isset($this->resolvers[$interface])) {
            if (!$class = $this->resolve(Resolver::class, $interface)) {
                throw Exceptional::NotFound('Interface ' . $interface . ' has no Archetype resolver');
            }

            /** @var class-string<Resolver> $class */
            $this->resolvers[$interface][] = new $class($interface);
        }
    }
}
