# Changelog

## [5.1.3] - 2025-02-21

### Changed

- `config:get` renamed to `extra:config:get`

## [5.1.2] - 2025-02-21

### Added

- Github actions for tests.

### Fixed

- Code formatting

## [5.1.1] - 2024-11-18

### Fixed

- Prevent duplicates via "site:add" by [mbrodala](https://github.com/mbrodala)

### Added

- License info on all files.

## [5.1.0] - 2024-10-28

### Added

- Support for collation and charset - as needed for Matomo 5.1.2

### Changed

- Drop and Create database functions.

## [5.0.8] - 2024-10-28

### Fixed

- Running "matomo:install" without "--force" fails: "getHelper can not be used" #41
- Missing tables after "matomo:install" #44

## [5.0.4] - 2024-08-30

### Added

- Marketplace cover

## [5.0.3] - 2024-04-08

### Removed

* Setting maintenance mode breaks, removing.

## [5.0.2] - 2024-04-08

### Added

* Extra Tools menu in Administration interface.
* Documentation page at Administration -> Extra Tools -> Documentation.

### Changed

* `phpinfo()` page moved to: Administration -> Extra Tools -> Phpinfo.
* Archive validations page moved to: Administration -> Extra Tools -> Invalidations.

### Removed

* Old changelog entries removed, to make it simpler to follow the new format. The old was also badly updated (sorry for that).
