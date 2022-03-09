# Archetype

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/archetype?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/archetype.svg?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/archetype.svg?style=flat)](https://packagist.org/packages/decodelabs/archetype)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/decodelabs/archetype/PHP%20Composer)](https://github.com/decodelabs/archetype/actions/workflows/php.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/archetype?style=flat)](https://packagist.org/packages/decodelabs/archetype)

Simple named library loader for PHP

## Installation

Install via Composer:

```bash
composer require decodelabs/archetype
```

### PHP version

_Please note, the final v1 releases of all Decode Labs libraries will target **PHP8** or above._

Current support for earlier versions of PHP will be phased out in the coming months.


## Usage

### Importing

Archetype uses [Veneer](https://github.com/decodelabs/veneer) to provide a unified frontage under <code>DecodeLabs\Archetype</code>.
You can access all the primary functionality via this static frontage without compromising testing and dependency injection.


### Functionality

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

Archetype uses a hierarchy of <code>Resolvers</code> to turn a name into a class. By default, the <code>Handler</code> will fall back on a <code>Resolver\Local</code> instance that simply locates the named class within the namespace of the associated interface.

In the above example, the implementations of the <code>My\Library\Thing</code> can be found at <code>My\Library\Thing\\*</code>.


### Custom resolvers

The <code>Resolver\Archetype</code> implementation however will also automatically look for custom <code>Resolver</code> classes in the same location as the target interface, named <code>\<Interface\>Archetype</code>.

The following example will replace the default functionality and cause Archetype to look in a different location for the <code>Thing</code> implementations:

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

Multiple <code>Resolver</code> instances can be stacked against a single interface, called in series based on the priority they request, the first one to return a non-null class name wins.

Alternative <code>Resolvers</code> can be loaded with:

```php
use DecodeLabs\Archetype;
use My\Library\Resolver\Alternative as AlternativeResolver;

Archetype::register(new AlternativeResolver());
```

### File lookup

<code>Resolvers</code> that also implement the <code>Finder</code> interface can define the means to lookup a file path based on the provided name, against the space defined by the target interface.

This can be useful when the _space_ that is defined by the root interface may contain assets aside from PHP code.

It is down to the implementation to decide how to map names to file paths (there are no pre-built default <code>Finder</code> classes).

Example:

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
