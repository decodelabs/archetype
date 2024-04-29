* Improved namespace mapping algorithm
* Upgraded namespace aliases to mount at any level
* Enable recursive aliases
* Added priority to aliases

## v0.3.3 (2024-04-29)
* Fixed Veneer stubs in gitattributes

## v0.3.2 (2024-04-29)
* Fixed Generic Resolver class scanner
* Added namespace map filter check

## v0.3.1 (2024-04-26)
* Fixed namespaceMap accessor

## v0.3.0 (2024-04-26)
* Created Namespace Mapping system
* Removed Extensions
* Restructured class layout
* Renamed Finder to FileFinder
* Renamed DefaultResolver to DefaultName
* Renamed NamespaceMap to NamespacedList
* Updated dependency list

## v0.2.22 (2023-11-27)
* Fixed scanClasses() iteration value generic type
* Made PHP8.1 minimum version

## v0.2.21 (2023-11-09)
* Simplified ArchetypeResolver Initialization

## v0.2.20 (2023-11-08)
* Added unique option to register()
* Added getResolvers() and getNormalizers() to Handler
* Improved NamespaceMap dump structure

## v0.2.19 (2023-11-08)
* Added NamespaceMap structure
* Added namespace priority to extenstions

## v0.2.18 (2023-11-08)
* Improved null handling in resolve()

## v0.2.17 (2023-11-07)
* Added support for resolving array of names
* Look for namespace short name as default resolution

## v0.2.16 (2023-10-30)
* Improved default interface resolution

## v0.2.15 (2023-10-30)
* Use interface for default resolver if instantiable

## v0.2.14 (2023-10-29)
* Added default resolver interface for nameless resolution
* Refactored package file structure

## v0.2.13 (2023-09-26)
* Converted phpstan doc comments to generic

## v0.2.12 (2022-12-06)
* Improved resolution subclass check

## v0.2.11 (2022-11-28)
* Added default fallback to resolve handler

## v0.2.10 (2022-11-22)
* Added normalizer interface structure
* Renamed Local resolver to Generic
* Added extension namespaces to Generic resolver
* Fixed PHP8.1 testing
* Migrated to use effigy in CI workflow

## v0.2.9 (2022-10-28)
* Added interface check to class scanner

## v0.2.8 (2022-10-17)
* Auto register local resolver when registering extension

## v0.2.7 (2022-10-17)
* Added class scanner interface

## v0.2.6 (2022-10-12)
* Added Extension Resolver

## v0.2.5 (2022-09-28)
* Added check to resolve $name as subclass

## v0.2.4 (2022-09-27)
* Updated Veneer stub
* Updated composer check script

## v0.2.3 (2022-09-27)
* Updated Veneer dependency

## v0.2.2 (2022-08-26)
* Fixed resolve() PHPStan return hint
* Updated CI environment

## v0.2.1 (2022-08-23)
* Added concrete types to all members

## v0.2.0 (2022-08-23)
* Removed PHP7 compatibility
* Updated ECS to v11
* Updated PHPUnit to v9

## v0.1.1 (2022-03-09)
* Transitioned from Travis to GHA
* Updated PHPStan and ECS dependencies

## v0.1.0 (2021-11-18)
* Added initial boilerplate
* Built Resolver Handler and structure
* Added simple Finder interface
* Built Archetype and Local Resolver implementations
