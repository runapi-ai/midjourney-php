# Changelog

## [v0.4.0](https://github.com/runapi-ai/midjourney-php/releases/tag/v0.4.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.3.1](https://github.com/runapi-ai/midjourney-php/releases/tag/v0.3.1) - 2026-09-04

### Changed
- Return the same terminal helper response whether the request completes directly or through an accepted Task.


## [v0.3.0](https://github.com/runapi-ai/midjourney-php/releases/tag/v0.3.0) - 2026-07-22

### Added
- Add a typed extendVideo resource with public request validation and task polling.


## [v0.2.0](https://github.com/runapi-ai/midjourney-php/releases/tag/v0.2.0) - 2026-07-20

### Added
- Add the synchronous prompt shortening resource and typed response to the Midjourney Composer package.


## [v0.1.0](https://github.com/runapi-ai/midjourney-php/releases/tag/v0.1.0) - 2026-07-17

### Added
- Add the Midjourney Composer package for PHP image, video, and helper workflows.
