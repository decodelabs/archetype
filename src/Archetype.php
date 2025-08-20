<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs;

use DecodeLabs\Archetype\NamespaceMap;
use DecodeLabs\Archetype\Normalizer;
use DecodeLabs\Archetype\Normalizer\Generic as GenericNormalizer;
use DecodeLabs\Archetype\Resolver;
use DecodeLabs\Archetype\Resolver\Archetype as ArchetypeResolver;
use DecodeLabs\Archetype\Resolver\DefaultName as DefaultNameResolver;
use DecodeLabs\Archetype\Resolver\FileFinder;
use DecodeLabs\Archetype\Resolver\Scanner as ScannerResolver;
use DecodeLabs\Kingdom\PureService;
use DecodeLabs\Kingdom\PureServiceTrait;
use Generator;
use ReflectionClass;

class Archetype implements PureService
{
    use PureServiceTrait;

    public protected(set) NamespaceMap $namespaces;

    /**
     * @var array<class-string,array<Resolver>>
     */
    public protected(set) array $resolvers = [];

    /**
     * @var array<class-string,array<Normalizer>>
     */
    public protected(set) array $normalizers = [];

    public function __construct()
    {
        $this->namespaces = new NamespaceMap();
    }

    public function getNamespaceMap(): NamespaceMap
    {
        return $this->namespaces;
    }


    public function register(
        Resolver|Normalizer $item,
        bool $unique = false
    ): void {
        /** @var string $interface */
        $interface = $item->getInterface();

        if (
            !interface_exists($interface) &&
            !class_exists($interface)
        ) {
            throw Exceptional::{'./Archetype/NotFound'}(
                message: 'Interface ' . $interface . ' does not exist'
            );
        }

        if (
            !$unique &&
            $interface !== Resolver::class
        ) {
            $this->ensureResolver($interface);
        }

        if ($item instanceof Resolver) {
            $item->setNamespaceMap($this->getNamespaceMap());
            $list = &$this->resolvers;
        } else {
            $list = &$this->normalizers;
        }

        if ($unique) {
            $list[$interface] = [$item];
            return;
        }

        $reorder = !empty($list[$interface] ?? null);
        $list[$interface][] = $item;

        if ($reorder) {
            usort($list[$interface], function ($a, $b) {
                return $a->getPriority() <=> $b->getPriority();
            });
        }
    }

    public function unregister(
        Resolver|Normalizer $item
    ): void {
        $interface = $item->getInterface();

        if ($item instanceof Resolver) {
            $list = &$this->resolvers;
        } else {
            $list = &$this->normalizers;
        }

        foreach ($list[$interface] ?? [] as $key => $registered) {
            if ($registered === $item) {
                unset($list[$interface][$key]);
                break;
            }
        }
    }


    /**
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



    public function map(
        string $root,
        string $namespace,
        int $priority = 0
    ): void {
        $this->getNamespaceMap()->add($root, $namespace, $priority);
    }

    public function alias(
        string $interface,
        string $alias,
        int $priority = 0
    ): void {
        $this->getNamespaceMap()->addAlias($interface, $alias, $priority);
    }


    /**
     * @template T of object
     * @param class-string<T> $interface
     * @param string|array<string|null>|null $names
     * @param class-string<T>|callable(class-string<T>): class-string<T>|null $default
     * @return class-string<T>
     */
    public function resolve(
        string $interface,
        string|array|null $names = null,
        string|callable|null $default = null
    ): string {
        if (null === ($class = $this->tryResolve($interface, $names, $default))) {
            if (is_array($names)) {
                $names = implode(', ', $names);
            }

            throw Exceptional::{'./Archetype/NotFound'}(
                message: 'Could not resolve ' . ($names ? '"' . $names . '"' : 'default') . ' for interface ' . $interface
            );
        }

        return $class;
    }

    /**
     * @template T of object
     * @param class-string<T> $interface
     * @param string|array<string|null>|null $names
     * @param class-string<T>|callable(class-string<T>): class-string<T>|null $default
     * @return class-string<T>
     */
    public function tryResolve(
        string $interface,
        string|array|null $names = null,
        string|callable|null $default = null
    ): ?string {
        if (is_string($names)) {
            $names = [$names];
        }

        if ($names === []) {
            $names = null;
        }

        // Name is a classname already
        foreach ($names ?? [] as $name) {
            if (
                $name !== null &&
                class_exists($name) &&
                $this->isResolved($interface, $name)
            ) {
                /** @var class-string<T> $name */
                return $name;
            }
        }


        // Make sure there's at least one resolver for interface
        $this->ensureResolver($interface);

        foreach ($names ?? [] as $i => $name) {
            if ($name === null) {
                continue;
            }

            $names[$i] = $this->normalize($interface, $name);
        }

        $nameList = $names ?? [null];

        foreach ($this->resolvers[$interface] as $resolver) {
            $class = null;

            foreach ($nameList as $name) {
                if ($name === null) {
                    if (!$resolver instanceof DefaultNameResolver) {
                        continue;
                    }

                    $class = $resolver->resolveDefault();
                } else {
                    $class = $resolver->resolve($name);
                }

                if (
                    $class !== null &&
                    class_exists($class)
                ) {
                    return $this->checkResolution($interface, $class);
                }
            }
        }


        // Default
        if (
            !is_string($default) &&
            $default !== null
        ) {
            $default = (string)$default($interface);
        }

        if ($default !== null) {
            return $this->checkResolution($interface, $default);
        }

        if (
            $names === null &&
            (new ReflectionClass($interface))->isInstantiable()
        ) {
            return $interface;
        }

        return null;
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
     * @param class-string $interface
     */
    public function findFile(
        string $interface,
        string $name
    ): string {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (!$resolver instanceof FileFinder) {
                continue;
            }

            if (null !== ($path = $resolver->findFile($name))) {
                if (!is_file($path)) {
                    continue;
                }

                return $path;
            }
        }

        throw Exceptional::{'./Archetype/NotFound'}(
            message: 'Could not find file "' . $name . '" in namespace ' . $interface
        );
    }


    /**
     * @template T of object
     * @param class-string<T> $interface
     * @return Generator<string, class-string<T>>
     */
    public function scanClasses(
        string $interface
    ): Generator {
        $this->ensureResolver($interface);

        foreach ($this->resolvers[$interface] as $resolver) {
            if (!$resolver instanceof ScannerResolver) {
                continue;
            }

            foreach ($resolver->scanClasses() as $path => $class) {
                if (!is_a($class, $interface, true)) {
                    continue;
                }

                /** @var class-string<T> $class */
                yield $path => $class;
            }
        }
    }



    /**
     * @param class-string $interface
     */
    protected function ensureResolver(
        string $interface
    ): void {
        if (!isset($this->resolvers[$interface])) {
            if ($interface === Resolver::class) {
                $class = ArchetypeResolver::class;
            } elseif (!$class = $this->resolve(Resolver::class, $interface)) {
                throw Exceptional::{'./Archetype/NotFound'}(
                    message: 'Interface ' . $interface . ' has no Archetype resolver'
                );
            }

            /** @var class-string<Resolver> $class */
            $this->resolvers[$interface][] = $resolver = new $class($interface);
            $resolver->setNamespaceMap($this->getNamespaceMap());
        }
    }
}
