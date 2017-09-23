# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]
### Added
### Changed

## [3.0.1] - 2017-09-23
### Added
### Changed
- Bugfix: `Endobox::create` should be static

## [3.0.0] - 2017-09-23
### Added
- `Endobox` bootstrap class
### Changed
- Use a bootstrap class and inject `Factory` dependencies
- Fix minor bug in `Factory`: `NullRenderer` was being instantiated multiple times for no reason
- Travis-ci using test.sh and installing `require-dev` packages

## [2.4.0] - 2017-03-02
### Added
- Boxes can now be cloned (using `clone`)

### Changed
- Bugfix: Counter variable from foreach loop (called `$x`) was visible inside templates

## [2.3.2] - 2017-03-01
### Added
### Changed
- Cleaner box factory using a DI container ([pimple](http://pimple.sensiolabs.org)) for the Parsedown libs
- Get rid of copy-paste `MarkdownExtraRendererDecorator` class

## [2.3.1] - 2017-03-01
### Added
### Changed
- Throw `RuntimeException` if cycle (endless loop) is detected at render time
- More unit tests
- Improved README with nice figures

## [2.3.0] - 2017-02-26
### Added
Feature: Support assigning closures as data

### Changed
## [2.2.0] - 2017-02-26
### Added
- Feature: Set data via object properties
- This very CHANGELOG

### Changed
- Better README
- More comments in code
