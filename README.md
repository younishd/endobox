# endobox

Awww this PHP template engine is so cute.

[![Build Status](https://travis-ci.org/younishd/endobox.svg?branch=v2)](https://travis-ci.org/younishd/endobox)
[![Code Climate](https://codeclimate.com/github/younishd/endobox/badges/gpa.svg)](https://codeclimate.com/github/younishd/endobox)

## Highlights

- Simple, concise API
- Native PHP 7 syntax
- [Shared data](#shared-data) across templates
- [Nesting](#nesting) and [Chaining](#chaining)
- [Markdown](https://github.com/erusev/parsedown "using Parsedown") and
[Markdown Extra](https://github.com/erusev/parsedown-extra "using Parsedown Extra") support
- Assign [Closures](#closures) just like data

## Install

```bash
composer require younishd/endobox
```

## Hello world

```php
$endobox = new endobox\Factory('path/to/templates');

$box = $endobox('hello'); // omit extension

echo $box->render([ 'subject' => 'world' ]); // assign data directly via render
```

The template `hello.php` could look like this:

```php
<h1>Hello <?= $subject ?></h1>
```

Note that `<?=` is syntactic sugar for `<?php echo`.

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
