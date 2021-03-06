# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]

### Added
- Link all chained boxes by calling `->link()` or its shorthand `()` on a `Box` object.
- Late data assignment during render. You can assign data at render time and it will be taken into account by the engine.
- Inside templates, bind `$this` to corresponding `Box` object.

### Changed
- Drop deprecated `entangle()` in favor of `link()`
- Auto link assigned boxes. Assigning an object of type `Box` will automatically link it. This is analogous to linking nested boxes.


## [4.3.0] - 2020-06-24

### Added
- Invoke `Box` with `array` to assign data.

### Changed


## [4.2.0] - 2020-01-01

### Added
- `$endobox('foo')('bar')` is now equivalent to `($foo = $endobox->create('foo'))->append($endobox->create('bar')->link($foo))`
- Add `Box::create()` in the same fashion as the `$box()` function within templates

### Changed
- Overload `Box::append()` and `Box::prepend()`: argument can now be of type `Box` or `string` where the latter will instantiate a new `Box` before appending/prepending it


## [4.1.0] - 2019-11-09

### Added
- Add `link()` as alias for the cryptic (and now deprecated) `entangle()`
- Render partial templates with `$box()` or `$b()` (i.e., nesting)

### Changed
- Improved documentation in single README file


## [4.0.0] - 2019-01-23

### Added
- Render variable as `$markdown()`
- Assigned closures can now be called like a function and be given arguments. What a time we live in!
- `composer test` replacing `test.sh`

### Changed
- PHP template will now stfu when `@` operator is used
- Fix sneaky bug in union-find path compression
- Make library (even more) DI-friendly: Get rid of container code and provide a Facade with a good default combination of appropriate dependencies (We learn something every day.)


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
