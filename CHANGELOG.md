# Changelog

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0](https://github.com/coniadev/wire/releases/tag/0.1.0) - 2023-11-11

Initial release.

### Added

- The `Wire` factory, which produces `Creator`, `CallableResolver` and `ContstructorResolver` instances.
- The `Inject` attribute.
- The `Call` attribute.
- The ability to be combined with PSR-11 containers.

## [0.2.0](https://github.com/coniadev/wire/releases/tag/0.2.0) - 2024-01-05

Add predefined types.

### Added

- The `predefinedTypes` parameter to `Creator::create`.
- The `predefinedTypes` parameter to `CallableResolver::resolve`.
- The `predefinedTypes` parameter to `ConstructorResolver::resolve`.
