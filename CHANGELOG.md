# Changelog

All notable changes to this project will be documented in this file.<br>
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased
- Simplified CI workflow

---

### [v0.4.1](https://github.com/decodelabs/archetype/commits/v0.4.1) - 10th September 2025

- Upgraded Kingdom to v0.2

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.4.0...v0.4.1)

---

### [v0.4.0](https://github.com/decodelabs/archetype/commits/v0.4.0) - 20th August 2025

- Removed Veneer dependency
- Added Kingdom Service support

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.11...v0.4.0)

---

### [v0.3.11](https://github.com/decodelabs/archetype/commits/v0.3.11) - 16th July 2025

- Applied ECS formatting to all code

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.10...v0.3.11)

---

### [v0.3.10](https://github.com/decodelabs/archetype/commits/v0.3.10) - 6th June 2025

- Switched to Nuance for dump handling
- Upgraded Exceptional to v0.6

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.9...v0.3.10)

---

### [v0.3.9](https://github.com/decodelabs/archetype/commits/v0.3.9) - 14th April 2025

- Added contains() and localize() to NamespaceList
- Improved Exception syntax
- Updated dependencies

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.8...v0.3.9)

---

### [v0.3.8](https://github.com/decodelabs/archetype/commits/v0.3.8) - 12th February 2025

- Upgraded PHPStan to v2
- Added @phpstan-require-implements constraints
- Updated Veneer dependency and Stub
- Added PHP8.4 to CI workflow
- Made PHP8.4 minimum version

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.7...v0.3.8)

---

### [v0.3.7](https://github.com/decodelabs/archetype/commits/v0.3.7) - 31st July 2024

- Allow abstract classes as scanner interfaces

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.6...v0.3.7)

---

### [v0.3.6](https://github.com/decodelabs/archetype/commits/v0.3.6) - 17th July 2024

- Updated Veneer dependency

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.5...v0.3.6)

---

### [v0.3.5](https://github.com/decodelabs/archetype/commits/v0.3.5) - 21st May 2024

- Improved default name resolver

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.4...v0.3.5)

---

### [v0.3.4](https://github.com/decodelabs/archetype/commits/v0.3.4) - 29th April 2024

- Improved namespace mapping algorithm
- Upgraded namespace aliases to mount at any level
- Enable recursive aliases
- Added priority to aliases

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.3...v0.3.4)

---

### [v0.3.3](https://github.com/decodelabs/archetype/commits/v0.3.3) - 29th April 2024

- Fixed Veneer stubs in gitattributes

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.2...v0.3.3)

---

### [v0.3.2](https://github.com/decodelabs/archetype/commits/v0.3.2) - 29th April 2024

- Fixed Generic Resolver class scanner
- Added namespace map filter check

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.1...v0.3.2)

---

### [v0.3.1](https://github.com/decodelabs/archetype/commits/v0.3.1) - 26th April 2024

- Fixed namespaceMap accessor

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.3.0...v0.3.1)

---

### [v0.3.0](https://github.com/decodelabs/archetype/commits/v0.3.0) - 26th April 2024

- Created Namespace Mapping system
- Removed Extensions
- Restructured class layout
- Renamed Finder to FileFinder
- Renamed DefaultResolver to DefaultName
- Renamed NamespaceMap to NamespacedList
- Updated dependency list

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.22...v0.3.0)

---

### [v0.2.22](https://github.com/decodelabs/archetype/commits/v0.2.22) - 27th November 2023

- Fixed scanClasses() iteration value generic type
- Made PHP8.1 minimum version

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.21...v0.2.22)

---

### [v0.2.21](https://github.com/decodelabs/archetype/commits/v0.2.21) - 9th November 2023

- Simplified ArchetypeResolver Initialization

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.20...v0.2.21)

---

### [v0.2.20](https://github.com/decodelabs/archetype/commits/v0.2.20) - 8th November 2023

- Added unique option to register()
- Added getResolvers() and getNormalizers() to Handler
- Improved NamespaceMap dump structure

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.19...v0.2.20)

---

### [v0.2.19](https://github.com/decodelabs/archetype/commits/v0.2.19) - 8th November 2023

- Added NamespaceMap structure
- Added namespace priority to extenstions

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.18...v0.2.19)

---

### [v0.2.18](https://github.com/decodelabs/archetype/commits/v0.2.18) - 8th November 2023

- Improved null handling in resolve()

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.17...v0.2.18)

---

### [v0.2.17](https://github.com/decodelabs/archetype/commits/v0.2.17) - 7th November 2023

- Added support for resolving array of names
- Look for namespace short name as default resolution

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.16...v0.2.17)

---

### [v0.2.16](https://github.com/decodelabs/archetype/commits/v0.2.16) - 30th October 2023

- Improved default interface resolution

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.15...v0.2.16)

---

### [v0.2.15](https://github.com/decodelabs/archetype/commits/v0.2.15) - 30th October 2023

- Use interface for default resolver if instantiable

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.14...v0.2.15)

---

### [v0.2.14](https://github.com/decodelabs/archetype/commits/v0.2.14) - 29th October 2023

- Added default resolver interface for nameless resolution
- Refactored package file structure

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.13...v0.2.14)

---

### [v0.2.13](https://github.com/decodelabs/archetype/commits/v0.2.13) - 26th September 2023

- Converted phpstan doc comments to generic

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.12...v0.2.13)

---

### [v0.2.12](https://github.com/decodelabs/archetype/commits/v0.2.12) - 6th December 2022

- Improved resolution subclass check

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.11...v0.2.12)

---

### [v0.2.11](https://github.com/decodelabs/archetype/commits/v0.2.11) - 28th November 2022

- Added default fallback to resolve handler

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.10...v0.2.11)

---

### [v0.2.10](https://github.com/decodelabs/archetype/commits/v0.2.10) - 22nd November 2022

- Added normalizer interface structure
- Renamed Local resolver to Generic
- Added extension namespaces to Generic resolver
- Fixed PHP8.1 testing
- Migrated to use effigy in CI workflow

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.9...v0.2.10)

---

### [v0.2.9](https://github.com/decodelabs/archetype/commits/v0.2.9) - 28th October 2022

- Added interface check to class scanner

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.8...v0.2.9)

---

### [v0.2.8](https://github.com/decodelabs/archetype/commits/v0.2.8) - 17th October 2022

- Auto register local resolver when registering extension

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.7...v0.2.8)

---

### [v0.2.7](https://github.com/decodelabs/archetype/commits/v0.2.7) - 17th October 2022

- Added class scanner interface

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.6...v0.2.7)

---

### [v0.2.6](https://github.com/decodelabs/archetype/commits/v0.2.6) - 12th October 2022

- Added Extension Resolver

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.5...v0.2.6)

---

### [v0.2.5](https://github.com/decodelabs/archetype/commits/v0.2.5) - 28th September 2022

- Added check to resolve $name as subclass

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.4...v0.2.5)

---

### [v0.2.4](https://github.com/decodelabs/archetype/commits/v0.2.4) - 27th September 2022

- Updated Veneer stub
- Updated composer check script

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.3...v0.2.4)

---

### [v0.2.3](https://github.com/decodelabs/archetype/commits/v0.2.3) - 27th September 2022

- Updated Veneer dependency

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.2...v0.2.3)

---

### [v0.2.2](https://github.com/decodelabs/archetype/commits/v0.2.2) - 26th August 2022

- Fixed resolve() PHPStan return hint
- Updated CI environment

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.1...v0.2.2)

---

### [v0.2.1](https://github.com/decodelabs/archetype/commits/v0.2.1) - 23rd August 2022

- Added concrete types to all members

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.2.0...v0.2.1)

---

### [v0.2.0](https://github.com/decodelabs/archetype/commits/v0.2.0) - 23rd August 2022

- Removed PHP7 compatibility
- Updated ECS to v11
- Updated PHPUnit to v9

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.1.1...v0.2.0)

---

### [v0.1.1](https://github.com/decodelabs/archetype/commits/v0.1.1) - 9th March 2022

- Transitioned from Travis to GHA
- Updated PHPStan and ECS dependencies

[Full list of changes](https://github.com/decodelabs/archetype/compare/v0.1.0...v0.1.1)

---

### [v0.1.0](https://github.com/decodelabs/archetype/commits/v0.1.0) - 18th November 2021

- Added initial boilerplate
- Built Resolver Handler and structure
- Added simple Finder interface
- Built Archetype and Local Resolver implementations
