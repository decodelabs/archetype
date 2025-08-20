<?php

/**
 * @package Archetype
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Archetype\Resolver;

use Composer\Autoload\ClassLoader;
use FilesystemIterator;
use Generator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @phpstan-require-implements Scanner
 */
trait ScannerTrait
{
    /**
     * @param class-string|null $interface
     * @return Generator<string, class-string>
     */
    protected function scanNamespaceClasses(
        string $namespace,
        ?string $interface = null
    ): Generator {
        $parts = explode('\\', $namespace);

        foreach (ClassLoader::getRegisteredLoaders() as $loader) {
            $prefixes = $loader->getPrefixesPsr4();
            $prefixParts = $parts;
            $pathParts = [];

            while (!empty($prefixParts)) {
                $prefix = implode('\\', $prefixParts) . '\\';

                if (isset($prefixes[$prefix])) {
                    $relPath = implode('/', $pathParts);

                    foreach ($prefixes[$prefix] as $basePath) {
                        $path = $basePath . '/' . $relPath;
                        yield from $this->scanVendorPath($path, $namespace, $interface);
                    }
                }

                array_unshift($pathParts, array_pop($prefixParts));
            }
        }
    }

    /**
     * @param class-string|null $interface
     * @return Generator<string, class-string>
     */
    protected function scanVendorPath(
        string $path,
        string $namespace,
        ?string $interface = null
    ): Generator {
        if (!is_dir($path)) {
            return;
        }

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path,
                FilesystemIterator::KEY_AS_PATHNAME |
                FilesystemIterator::CURRENT_AS_SELF |
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var RecursiveDirectoryIterator $item */
        foreach ($it as $item) {
            $pathName = $item->getPathname();
            $subName = $item->getSubPathname();

            if (substr($pathName, -4) !== '.php') {
                continue;
            }

            $className = str_replace('/', '\\', substr($subName, 0, -4));
            $class = $namespace . '\\' . $className;

            if (!class_exists($class)) {
                continue;
            }

            if ($interface !== null) {
                if (!is_a($class, $interface, true)) {
                    continue;
                }
            }

            if (false !== ($realPath = realpath($pathName))) {
                $pathName = $realPath;
            }

            yield $pathName => $class;
        }
    }
}
