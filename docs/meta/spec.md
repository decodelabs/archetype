# Archetype — Package Specification

> **Cluster:** `runtime`  
> **Language:** `php`  
> **Milestone:** `m1`  
> **Repo:** `https://github.com/decodelabs/archetype`  
> **Role:** Class resolution

This document describes the purpose, contracts, and design of **Archetype** within the Decode Labs ecosystem.

It is aimed at:

- Developers **using** Archetype in their own applications or libraries.
- Contributors **maintaining or extending** Archetype.
- Tools and AI assistants that need to reason about its behaviour.

---

## 1. Overview

### 1.1 Purpose

Archetype provides a **generic frontend to resolving implementation classes for a named interface** with an extensible plugin architecture. It enables libraries to load arbitrary extension classes based on naming conventions, making it ideal for Factory patterns and plugin-oriented architectures.

Archetype maps names to classes through a customizable resolver hierarchy, allowing fast and reliable means to create loosely coupled plugin ecosystems. It supports:

- **Name-to-class resolution** for any interface or class.
- **Extensible resolver system** with priority-based ordering.
- **Automatic namespace mapping** with configurable namespace aliases.
- **Class scanning** to discover all implementations of an interface.
- **File finding** for non-PHP assets associated with interface namespaces.
- **Name normalization** through pluggable normalizers.

### 1.2 Non-Goals

Archetype does **not**:

- Implement dependency injection or service container functionality (see `decodelabs/kingdom` and `decodelabs/pandora`).
- Provide class instantiation (only class name resolution).
- Handle autoloading (relies on PHP's autoloader).
- Manage class metadata or reflection beyond basic type checking.
- Provide caching or performance optimization layers.

Archetype is a **resolution layer** that maps names to class names, not a container or factory implementation.

---

## 2. Role in the Ecosystem

### 2.1 Cluster & Positioning

- **Cluster:** `runtime`
- Archetype is a **foundational utility** used by packages that need to resolve class names from user-provided names or configuration.

It sits at a low level in the dependency graph:

- It depends on `decodelabs/exceptional` for error handling.
- It depends on `decodelabs/kingdom` for service container integration (PureService).
- It depends on `decodelabs/nuance` for type inspection utilities.
- Higher-level packages (e.g., `decodelabs/slingshot`, `decodelabs/commandment`, `decodelabs/pandora`) use Archetype to resolve handler classes, command classes, and service implementations.

### 2.2 Typical Usage Contexts

Typical places Archetype appears:

- **Factory patterns** where user-provided names need to be resolved to concrete class names.
- **Plugin systems** where extensions are discovered and loaded by name.
- **Command dispatchers** that resolve command names to command handler classes.
- **Service containers** that resolve service names to implementation classes.
- **Template systems** that resolve component names to component classes.
- **Router systems** that resolve controller names to controller classes.

Archetype is intended to be used whenever a Decode Labs package or application needs to:

- resolve user-provided names to class names,
- support extensible plugin architectures,
- provide a consistent interface for class discovery across different packages.

---

## 3. Public Surface

> This section focuses on the conceptual API, not every symbol.

### 3.1 Key Types

The primary public types are:

- `DecodeLabs\Archetype`
  Main entry point for class resolution. Provides methods for resolving names to classes, registering custom resolvers and normalizers, managing namespace mappings, and scanning classes.

- `DecodeLabs\Archetype\Resolver`
  Interface for classes that resolve names to class names. Resolvers are registered per interface and called in priority order until one returns a non-null class name.

- `DecodeLabs\Archetype\Resolver\Archetype`
  Special resolver for resolving `Resolver` implementations themselves. Looks for classes named `{Interface}Archetype` in the same namespace as the interface.

- `DecodeLabs\Archetype\Resolver\Generic`
  Default resolver implementation that looks for classes in the namespace of the target interface. Supports class scanning via the `Scanner` interface.

- `DecodeLabs\Archetype\Resolver\FileFinder`
  Interface extending `Resolver` for resolvers that can also find file paths for names (useful for non-PHP assets).

- `DecodeLabs\Archetype\Resolver\Scanner`
  Interface extending `Resolver` for resolvers that can scan and list all classes resolvable under a namespace.

- `DecodeLabs\Archetype\Resolver\DefaultName`
  Interface extending `Resolver` for resolvers that can resolve a default class name when no name is provided.

- `DecodeLabs\Archetype\Normalizer`
  Interface for classes that normalize names before resolution (e.g., converting kebab-case to PascalCase).

- `DecodeLabs\Archetype\NamespaceMap`
  Manages namespace mappings and aliases. Used internally by resolvers to map interface namespaces to implementation namespaces.

- `DecodeLabs\Archetype\NamespaceList`
  Ordered list of namespaces with priority support.

### 3.2 Main Entry Points

The primary entry point is:

```php
$archetype = new DecodeLabs\Archetype();
$class = $archetype->resolve(MyInterface::class, 'MyClass');
```

Key methods:

- `resolve(string $interface, string|array|null $names, string|callable|null $default = null): string`
  Resolves a name (or array of names) to a class name for the given interface. Throws an exception if resolution fails.

- `tryResolve(string $interface, string|array|null $names, string|callable|null $default = null): ?string`
  Attempts to resolve a name to a class name. Returns `null` if resolution fails instead of throwing.

- `register(Resolver|Normalizer $item, bool $unique = false): void`
  Registers a custom resolver or normalizer for an interface. Resolvers are sorted by priority.

- `normalize(string $interface, string $name): string`
  Normalizes a name using registered normalizers for the interface.

- `map(string $root, string $namespace, int $priority = 0): void`
  Maps a root namespace to an implementation namespace.

- `alias(string $interface, string $alias, int $priority = 0): void`
  Creates an alias mapping for an interface namespace.

- `findFile(string $interface, string $name): string`
  Finds a file path for a name using resolvers that implement `FileFinder`.

- `scanClasses(string $interface): Generator<string, class-string>`
  Scans and yields all classes resolvable for an interface using resolvers that implement `Scanner`.

---

## 4. Dependencies

### 4.1 Decode Labs

- `decodelabs/exceptional` (^0.6.3)
  Used for error handling when resolution fails or invalid interfaces are provided.

- `decodelabs/kingdom` (^0.2)
  Archetype implements `PureService` from Kingdom for service container integration.

- `decodelabs/nuance` (^0.2)
  Used for type inspection utilities.

### 4.2 External

None. Archetype has no external dependencies beyond PHP itself.

---

## 5. Behaviour & Contracts

### 5.1 Invariants

- **Resolution consistency:** If `resolve()` succeeds for a given interface and name, subsequent calls with the same arguments will return the same class name (assuming no resolvers are registered/unregistered between calls).
- **Priority ordering:** Resolvers are always called in priority order (higher priority first).
- **Type safety:** Resolved classes are verified to implement or extend the target interface before being returned.
- **Namespace mapping:** Namespace mappings are applied consistently across all resolvers for an interface.
- **Pure service:** Archetype implements `PureService`, meaning it has no mutable global state and can be safely instantiated multiple times.

### 5.2 Input & Output Contracts

**`resolve()` and `tryResolve()`:**

- **Input:** 
  - `$interface`: Must be a valid class or interface name that exists.
  - `$names`: Optional string, array of strings, or `null`. If an array, each element is tried in order.
  - `$default`: Optional class name string or callable that returns a class name.
- **Output:** 
  - `resolve()`: Returns a class name string that implements or extends `$interface`. Throws `Exceptional\Archetype\NotFound` if resolution fails.
  - `tryResolve()`: Returns a class name string or `null` if resolution fails.
- **Side effects:** None (pure resolution).

**`register()`:**

- **Input:**
  - `$item`: Must implement `Resolver` or `Normalizer` and return a valid interface name from `getInterface()`.
  - `$unique`: If `true`, replaces all existing resolvers/normalizers for the interface.
- **Output:** `void`
- **Side effects:** Adds the resolver/normalizer to the internal registry, sorted by priority.

**`normalize()`:**

- **Input:**
  - `$interface`: Valid interface name.
  - `$name`: String name to normalize.
- **Output:** Normalized string name (or original if no normalizers match).
- **Side effects:** None.

**`findFile()`:**

- **Input:**
  - `$interface`: Valid interface name.
  - `$name`: String name to find file for.
- **Output:** File path string. Throws `Exceptional\Archetype\NotFound` if no file is found.
- **Side effects:** None.

**`scanClasses()`:**

- **Input:**
  - `$interface`: Valid interface name.
- **Output:** Generator yielding `[path => class-string]` pairs.
- **Side effects:** May perform filesystem scanning.

### 5.3 Error Handling

Archetype uses `decodelabs/exceptional` for error handling:

- `Exceptional\Archetype\NotFound` is thrown when:
  - An interface does not exist during registration.
  - Resolution fails and no default is provided.
  - File finding fails.
  - An interface has no resolver available.

- `Exceptional\UnexpectedValue` is thrown when:
  - A resolved class does not implement the target interface.
  - A resolved class is not instantiable when the interface itself is requested.

All errors are thrown as exceptions; there are no silent failures or error return codes.

---

## 6. Configuration & Extensibility

### 6.1 Resolver Registration

Custom resolvers can be registered via `register()`:

```php
$archetype->register(new MyCustomResolver());
```

Resolvers must:
- Implement `DecodeLabs\Archetype\Resolver`
- Return a valid interface name from `getInterface()`
- Return a priority integer from `getPriority()` (higher = called first)
- Implement `resolve(string $name): ?string` to return a class name or `null`

Resolvers are automatically sorted by priority when registered.

### 6.2 Normalizer Registration

Custom normalizers can be registered via `register()` or `registerCustomNormalizer()`:

```php
$archetype->registerCustomNormalizer(
    MyInterface::class,
    fn(string $name) => str_replace('-', '', ucwords($name, '-')),
    priority: 10
);
```

Normalizers must:
- Implement `DecodeLabs\Archetype\Normalizer`
- Return a valid interface name from `getInterface()`
- Return a priority integer from `getPriority()`
- Implement `normalize(string $name): ?string` to return a normalized name or `null`

### 6.3 Namespace Mapping

Namespace mappings allow resolvers to look in different namespaces than the interface namespace:

```php
$archetype->map('My\\Library', 'My\\Library\\Implementation');
```

Aliases provide alternative namespace paths:

```php
$archetype->alias(MyInterface::class, 'Some\\Other\\Namespace');
```

### 6.4 Automatic Resolver Discovery

Archetype automatically looks for resolver classes named `{Interface}Archetype` in the same namespace as the interface. For example, for `My\Library\Thing`, it will look for `My\Library\ThingArchetype`.

---

## 7. Interactions with Other Packages

### 7.1 Kingdom Integration

Archetype implements `PureService` from Kingdom, allowing it to be registered in service containers and injected as a dependency. It has no mutable global state, so multiple instances can coexist safely.

### 7.2 Slingshot

Slingshot uses Archetype to resolve handler classes for dependency injection invocations. It registers custom resolvers for handler interfaces.

### 7.3 Commandment

Commandment uses Archetype to resolve command names to command handler classes. It provides custom resolvers that scan command directories.

### 7.4 Pandora

Pandora uses Archetype to resolve service implementation classes from service names. It integrates with Archetype's namespace mapping system.

### 7.5 Other Packages

Many other Decode Labs packages use Archetype for class resolution:
- `decodelabs/genesis` - Resolves kernel and module classes
- `decodelabs/greenleaf` - Resolves controller classes
- `decodelabs/tagged` - Resolves component classes
- `decodelabs/harvest` - Resolves middleware classes
- `decodelabs/impulse` - Resolves event handler classes

---

## 8. Usage Examples

### 8.1 Basic Resolution

```php
use DecodeLabs\Archetype;

$archetype = new Archetype();

// Resolve a class name
$class = $archetype->resolve(Thing::class, 'Box');
$thing = new $class();
```

### 8.2 Factory Pattern

```php
namespace My\Library {
    use DecodeLabs\Archetype;

    interface Thing {}

    class Factory {
        public function __construct(
            private Archetype $archetype
        ) {}

        public function loadThing(string $name): Thing {
            $class = $this->archetype->resolve(Thing::class, $name);
            return new $class();
        }
    }
}

namespace My\Library\Thing {
    use My\Library\Thing;

    class Box implements Thing {}
    class Potato implements Thing {}
}

// Usage
$factory = new Factory(new Archetype());
$box = $factory->loadThing('Box');
$potato = $factory->loadThing('Potato');
```

### 8.3 Custom Resolver

```php
namespace My\Library {
    use DecodeLabs\Archetype\Resolver;

    class ThingArchetype implements Resolver {
        use ResolverTrait;

        public function getInterface(): string {
            return Thing::class;
        }

        public function getPriority(): int {
            return 10;
        }

        public function resolve(string $name): ?string {
            // Custom resolution logic
            return 'Some\\Other\\Namespace\\' . $name;
        }
    }
}
```

### 8.4 Multiple Name Attempts

```php
// Try multiple names in order
$class = $archetype->resolve(
    Thing::class,
    ['Box', 'Container', 'Item']
);
```

### 8.5 Class Scanning

```php
// Find all implementations
foreach ($archetype->scanClasses(Thing::class) as $path => $class) {
    echo "Found: $class at $path\n";
}
```

### 8.6 File Finding

```php
// Find associated files (e.g., templates, assets)
$path = $archetype->findFile(Thing::class, 'box');
```

---

## 9. Implementation Notes (for Contributors)

### 9.1 Resolver Priority System

Resolvers are stored in arrays keyed by interface name, with each array containing resolvers sorted by priority (descending). When `register()` is called, if resolvers already exist for the interface, the new resolver is added and the array is re-sorted.

### 9.2 Namespace Mapping Algorithm

The `NamespaceMap` class uses a recursive algorithm to apply namespace mappings and aliases. It walks up the namespace hierarchy, applying root mappings and alias lookups at each level. Wildcard aliases (`Interface\*`) are supported for one-level deep mappings.

### 9.3 Automatic Resolver Creation

When `ensureResolver()` is called for an interface that has no resolvers, Archetype:
1. First tries to resolve a custom resolver using `Resolver::class` and the interface name.
2. Falls back to `Generic` resolver if no custom resolver is found.
3. Creates and registers the resolver instance.

### 9.4 Type Safety

All resolved classes are verified using `is_subclass_of()` or by checking if the class is the interface itself and is instantiable. This ensures type safety at runtime.

### 9.5 PureService Implementation

Archetype implements `PureService` from Kingdom, meaning:
- No static mutable state
- No global registries
- Safe to instantiate multiple times
- Thread-safe (in PHP's single-threaded model)

---

## 10. Testing & Quality

### 10.1 Quality Scores

- **Code:** 4.5/5
- **README:** 3/5
- **Docs:** 0/5 (this spec addresses that)
- **Tests:** 0/5

### 10.2 Testing Strategy

Archetype should be tested for:

- **Resolution correctness:** All resolver types resolve names correctly.
- **Priority ordering:** Resolvers are called in the correct order.
- **Namespace mapping:** Mappings and aliases are applied correctly.
- **Error handling:** Appropriate exceptions are thrown for invalid inputs.
- **Type safety:** Resolved classes are verified to implement interfaces.
- **Edge cases:** Null names, empty arrays, non-existent classes, etc.

### 10.3 PHPStan Integration

Archetype uses `decodelabs/phpstan-decodelabs` for static analysis. The codebase should maintain PHPStan level 9 compliance.

---

## 11. Roadmap & Future Ideas

Potential future enhancements:

- **Caching layer:** Cache resolution results to improve performance for repeated lookups.
- **Lazy resolution:** Support for lazy class loading without immediate resolution.
- **Metadata storage:** Store additional metadata about resolved classes (e.g., constructor parameters, dependencies).
- **Performance profiling:** Built-in profiling to identify slow resolvers.
- **Configuration file support:** Load namespace mappings from configuration files.

---

## 12. References

- [README.md](../../README.md) - User-facing documentation and examples
- [composer.json](../../composer.json) - Package metadata and dependencies
- [Kingdom Package](../kingdom/docs/meta/spec.md) - Service container integration
- [Slingshot Package](../slingshot/docs/meta/spec.md) - Dependency injection invoker that uses Archetype
- [Commandment Package](../commandment/docs/meta/spec.md) - Command dispatcher that uses Archetype

