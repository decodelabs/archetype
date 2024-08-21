<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy as Proxy;
use DecodeLabs\Veneer\ProxyTrait as ProxyTrait;
use DecodeLabs\Archetype\Handler as Inst;
use DecodeLabs\Archetype\NamespaceMap as Ref0;
use DecodeLabs\Archetype\Resolver as Ref1;
use DecodeLabs\Archetype\Normalizer as Ref2;
use Generator as Ref3;

class Archetype implements Proxy
{
    use ProxyTrait;

    const Veneer = 'DecodeLabs\\Archetype';
    const VeneerTarget = Inst::class;

    public static Inst $instance;

    public static function getNamespaceMap(): Ref0 {
        return static::$instance->getNamespaceMap();
    }
    public static function getResolvers(): array {
        return static::$instance->getResolvers();
    }
    public static function getNormalizers(): array {
        return static::$instance->getNormalizers();
    }
    public static function register(Ref1|Ref2 $item, bool $unique = false): void {}
    public static function unregister(Ref1|Ref2 $item): void {}
    public static function registerCustomNormalizer(string $interface, callable $normalizer, int $priority = 10): Ref2 {
        return static::$instance->registerCustomNormalizer(...func_get_args());
    }
    public static function map(string $root, string $namespace, int $priority = 0): void {}
    public static function alias(string $interface, string $alias, int $priority = 0): void {}
    public static function resolve(string $interface, array|string|null $names = NULL, callable|string|null $default = NULL): string {
        return static::$instance->resolve(...func_get_args());
    }
    public static function tryResolve(string $interface, array|string|null $names = NULL, callable|string|null $default = NULL): ?string {
        return static::$instance->tryResolve(...func_get_args());
    }
    public static function normalize(string $interface, string $name): string {
        return static::$instance->normalize(...func_get_args());
    }
    public static function findFile(string $interface, string $name): string {
        return static::$instance->findFile(...func_get_args());
    }
    public static function scanClasses(string $interface): Ref3 {
        return static::$instance->scanClasses(...func_get_args());
    }
};
