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

    public const Veneer = 'DecodeLabs\\Archetype';
    public const VeneerTarget = Inst::class;

    protected static Inst $_veneerInstance;

    public static function getNamespaceMap(): Ref0 {
        return static::$_veneerInstance->getNamespaceMap();
    }
    public static function getResolvers(): array {
        return static::$_veneerInstance->getResolvers();
    }
    public static function getNormalizers(): array {
        return static::$_veneerInstance->getNormalizers();
    }
    public static function register(Ref1|Ref2 $item, bool $unique = false): void {}
    public static function unregister(Ref1|Ref2 $item): void {}
    public static function registerCustomNormalizer(string $interface, callable $normalizer, int $priority = 10): Ref2 {
        return static::$_veneerInstance->registerCustomNormalizer(...func_get_args());
    }
    public static function map(string $root, string $namespace, int $priority = 0): void {}
    public static function alias(string $interface, string $alias, int $priority = 0): void {}
    public static function resolve(string $interface, array|string|null $names = NULL, callable|string|null $default = NULL): string {
        return static::$_veneerInstance->resolve(...func_get_args());
    }
    public static function tryResolve(string $interface, array|string|null $names = NULL, callable|string|null $default = NULL): ?string {
        return static::$_veneerInstance->tryResolve(...func_get_args());
    }
    public static function normalize(string $interface, string $name): string {
        return static::$_veneerInstance->normalize(...func_get_args());
    }
    public static function findFile(string $interface, string $name): string {
        return static::$_veneerInstance->findFile(...func_get_args());
    }
    public static function scanClasses(string $interface): Ref3 {
        return static::$_veneerInstance->scanClasses(...func_get_args());
    }
};
