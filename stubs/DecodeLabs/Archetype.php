<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;

use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Archetype\Handler as Inst;
use DecodeLabs\Archetype\Resolver as Ref0;
use Generator as Ref1;

class Archetype implements Proxy
{
    use ProxyTrait;

    const VENEER = 'DecodeLabs\Archetype';
    const VENEER_TARGET = Inst::class;

    public static Inst $instance;

    public static function register(Ref0 $resolver): void {}
    public static function unregister(Ref0 $resolver): void {}
    public static function resolve(string $interface, string $name): string {
        return static::$instance->resolve(...func_get_args());
    }
    public static function findFile(string $interface, string $name): string {
        return static::$instance->findFile(...func_get_args());
    }
    public static function scanClasses(string $interface): Ref1 {
        return static::$instance->scanClasses(...func_get_args());
    }
};
