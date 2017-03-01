# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
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
