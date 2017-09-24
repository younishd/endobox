![endobox](endobox.png "endobox")

[![Build Status](https://travis-ci.org/younishd/endobox.svg?branch=v2)](https://travis-ci.org/younishd/endobox)
[![Code Climate](https://codeclimate.com/github/younishd/endobox/badges/gpa.svg)](https://codeclimate.com/github/younishd/endobox)

## Highlights

- Simple, concise API
- Native PHP 7 syntax
- [Shared data](https://github.com/younishd/endobox/wiki/Shared-Data) across templates
- [Chaining](https://github.com/younishd/endobox/wiki/Chaining-and-Nesting#chaining) and [Nesting](https://github.com/younishd/endobox/wiki/Chaining-and-Nesting#nesting)
- [Markdown](https://github.com/younishd/endobox/wiki/Template-Types) support
- Assign [Closures](https://github.com/younishd/endobox/wiki/Assign-Data#assign-closures) just like data

## Installation

Using [composer](https://getcomposer.org):

```bash
composer require younishd/endobox
```

## Getting started

```php
$factory = endobox\Endobox::create('path/to/templates');

$box = $factory('hello'); // omit extension

echo $box->render([ 'subject' => 'world' ]); // assign data directly via render
```

The template `hello.php` could look like this:

```php
<h1>Hello <?= $subject ?></h1>
```

Note that `<?=` is syntactic sugar for `<?php echo`.

The output would be:

```html
<h1>Hello world</h1>
```

This example can be found in `demo/`.


## Documentation

Check out the [__wiki__](https://github.com/younishd/endobox/wiki).

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
