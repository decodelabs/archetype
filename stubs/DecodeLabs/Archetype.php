<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Archetype\Handler as Inst;
use DecodeLabs\Archetype\Normalizer as Ref1;
use DecodeLabs\Archetype\Resolver as Ref0;
use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use Generator as Ref2;

class Archetype implements Proxy
{
    use ProxyTrait;

    public const VENEER = 'DecodeLabs\Archetype';
    public const VENEER_TARGET = Inst::class;

    public static Inst $instance;

    public static function register(Ref0|Ref1 $item): void
    {
    }
    public static function unregister(Ref0|Ref1 $item): void
    {
    }
    public static function registerCustomNormalizer(string $interface, callable $normalizer, int $priority = 10): Ref1
    {
        return static::$instance->registerCustomNormalizer(...func_get_args());
    }
    public static function extend(string $interface, string $namespace): void
    {
    }
    public static function resolve(string $interface, string $name): string
    {
        return static::$instance->resolve(...func_get_args());
    }
    public static function normalize(string $interface, string $name): string
    {
        return static::$instance->normalize(...func_get_args());
    }
    public static function findFile(string $interface, string $name): string
    {
        return static::$instance->findFile(...func_get_args());
    }
    public static function scanClasses(string $interface): Ref2
    {
        return static::$instance->scanClasses(...func_get_args());
    }
}
