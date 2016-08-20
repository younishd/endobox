# endobox

_Simple PHP template engine._

[![Build Status](https://travis-ci.org/younishd/endobox.svg?branch=v2)](https://travis-ci.org/younishd/endobox)

---

## About

_endobox_ is a really simple template engine that uses native PHP syntax.

## Highlights

- Simple, concise API
- Native PHP syntax
- [Shared data](#shared-data) across templates
- [Markdown](https://github.com/erusev/parsedown "using Parsedown") and
[Markdown Extra](https://github.com/erusev/parsedown-extra "using Parsedown Extra") support

## Install

```bash
composer require younishd/endobox
```

## Usage

### Hello world

```php
$endobox = new endobox\Factory('path/to/templates');

$box = $endobox('hello'); // omit extension

echo $box->render([ 'subject' => 'world' ]); // assign data directly via render
```

The template `hello.php` could look like this:

```php
<h1>Hello <?= $subject ?></h1>
```

### File extensions

_endobox_ decides how to render a template according to the _file extension_:

- `.php` is rendered as a __PHP__ template.

- `.html` is rendered as a __static__ template and returned as is.

- `.md(x)` is parsed as a __Markdown__ (Extra) template.

- `.md(x).php` is first rendered as __PHP__ then parsed as __Markdown__ (Extra) template.

Any other extension is ignored.

> If a template name matches more than one file, the behavior is undefined.

### Assign data

```php
$box->assign([ 'foo' => 'bar' ]);
```

The array keys will appear as _simple variables_ inside the templates.

> __Make sure to use valid PHP variable names as keys when assigning data!__

```php
echo $box->render([ 'qux' => 'xyz' ]); // this is also possible
```

You can assign data directly via `render()` by passing an optional argument.

### Access data from within template

`template.php`

```php
<p>
<?= $foo ?>
</p>
```

Data is accessible via simple variables where the variable names correspond to the assigned array keys.

### Shared data

The simplest way to share data across templates is using the `entangle()` method.

```php
$box->entangle($another);
```

TODO more details here...

### Chaining

Linking some boxes together using `append()` and `prepend()`.

```php
$first->append($second)->append($third);
```
```php
$first($second)($third); // short for append
```
```php
$third->prepend($second)->prepend($first);
```

These 3 lines are equivalent, obviously.

Now, calling `render()` would return the concatenated results of the linked boxes.

> Note that data is __not__ shared between chained boxes by default. Each box still has its own data. (See [Shared data](#shared-data))

### Markdown

`template.md.php`

```php
# Lorem ipsum
<?= $foo ?>
```

This template will first print the content of `$foo` then it is parsed as Markdown.

> The same goes for Markdown _Extra_.

### Folders

```php
$endobox->add_folder('another/path/to/templates');
```

TODO description

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
