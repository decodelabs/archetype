# Archetype

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/archetype?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/archetype.svg?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/archetype.svg?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/archetype/integrate.yml?branch=develop)](https://github.com/decodelabs/archetype/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/archetype?style=flat)](https://packagist.org/packages/decodelabs/archetype)

### Simple class name resolution for PHP

Archetype provides a generic frontend to resolving implementation classes for a named interface with an extensible plugin architechture.

_Get news and updates on the [DecodeLabs blog](https://blog.decodelabs.com/tag/decodelabs-archetype)._

---

## Installation

Install via Composer:

```bash
composer require decodelabs/archetype
```

## Usage

Use Archetype when designing plugin oriented libraries that need to load arbitrary extension classes based on a naming convention.

By mapping names to classes in an extensible and customisable resolver structure, Archetype allows fast and reliable means to create loosely coupled plugin ecosystems.

Example:

```php

// Main library
namespace My\Library
{

    use DecodeLabs\Archetype;

    interface Thing {}

    class Factory {

        public static function loadThing(string $name): Thing
        {
            // Resolve name to class for Thing interface
            $class = Archetype::resolve(Thing::class, $name);

            return new $class();
        }
    }
}


// Thing implementations
namespace My\Library\Thing
{

    use My\Library\Thing;

    class Box implements Thing {}
    class Potato implements Thing {}
    class Dog implements Thing {}
}



// Calling code
namespace My\App
{

    use My\Library\Factory;

    $box = Factory::loadthing('Box');
    $potato = Factory::loadThing('Potato');
    $dog = Factory::loadThing('Dog');
}
```

## Resolvers

Archetype uses a hierarchy of `Resolvers` to turn a name into a class. By default, the `Handler` will fall back on a generic resolver that simply locates the named class within the namespace of the associated interface.

In the above example, the implementations of the `My\Library\Thing` can be found at `My\Library\Thing\\*`.


### Custom resolvers

The `Resolver\Archetype` implementation however will also automatically look for custom `Resolver` classes in the same location as the target interface, named `\<Interface\>Archetype`.

The following example will replace the default functionality and cause Archetype to look in a different location for the `Thing` implementations:

```php
namespace My\Library {

    use DecodeLabs\Archetype\Resolver;

    class ThingArchetype implements Resolver
    {

        public function getInterface(): string
        {
            return Thing::class;
        }

        public function getPriority(): int
        {
            return 10;
        }

        public function resolve(string $name): ?string
        {
            return 'Some\\Other\\Namespace\\'.$name;
        }
    }
}
```

### Multiple resolvers

Multiple `Resolver` instances can be stacked against a single interface, called in series based on the priority they request, the first one to return a non-null class name wins.

Alternative `Resolvers` can be loaded with:

```php
use DecodeLabs\Archetype;
use My\Library\Resolver\Alternative as AlternativeResolver;

Archetype::register(new AlternativeResolver());
```

### Class scanning

Some resolvers (including the default one) can scan and list all classes resolvable under a namespace.

```php
use DecodeLabs\Archetype;

foreach(Archetype::scanClasses(Thing::class) as $path => $class) {
    echo 'Found class: '.$class.' at '.$path.PHP_EOL;
}
```


### File lookup

`Resolvers` that also implement the `Finder` interface can define the means to lookup a file path based on the provided name, against the space defined by the target interface.

This can be useful when the _space_ that is defined by the root interface may contain assets aside from PHP code.

It is down to the implementation to decide how to map names to file paths (there are no pre-built default `Finder` classes).

Example:

```php
namespace My\Library {

    use DecodeLabs\Archetype\Finder;

    class ThingArchetype implements Finder
    {

        public function getInterface(): string
        {
            return Thing::class;
        }

        public function getPriority(): int
        {
            return 10;
        }

        public function resolve(string $name): ?string
        {
            return 'Some\\Other\\Namespace\\'.$name;
        }

        public function findFile(string $name): ?string
        {
            return './some/other/namespace/'.$name.'.jpg';
        }
    }
}


namespace My\App {

    use DecodeLabs\Archetype;
    use My\Library\Thing;

    $boxImagePath = Archetype::findFile(Thing::class, 'box');
}
```


## Licensing
Archetype is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
