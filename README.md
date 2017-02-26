# endobox

A really simple template engine that uses native PHP syntax.

[![Build Status](https://travis-ci.org/younishd/endobox.svg?branch=v2)](https://travis-ci.org/younishd/endobox)

## Highlights

- Simple, concise API
- Native PHP syntax
- [Shared data](#shared-data) across templates
- [Nesting](#nesting) and [Chaining](#chaining)
- [Markdown](https://github.com/erusev/parsedown "using Parsedown") and
[Markdown Extra](https://github.com/erusev/parsedown-extra "using Parsedown Extra") support
- Assign [Closures](#closures) just like data

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

_If a template name matches more than one file, the behavior is undefined. Just don't do it._

### Assign data

#### "I like arrays"

```php
$box->assign([ 'foo' => 'bar' ]);
```

The array keys will appear as _simple variables_ inside the templates.

_Make sure to use valid PHP variable names as keys when assigning data!_

```php
echo $box->render([ 'qux' => 'xyz' ]); // this is also possible
```

You can assign data directly via `render()` by passing an optional argument.

#### "Property syntax is my thing"

Alternatively, you can assign data using object property syntax.

```php
$box->foo = 'bar';
$box->qux = 'xyz';
```

It's really the same as using arrays.

#### Closures

You can assign __closures__ to a box just like data!

```php
$box->day = function(){ return date('l'); };
```

Inside your template file you do:

```php
<p>Today is <?= $day ?>.</p>
```

### Render

```php
echo $box->render();
```

Render a box along with everything that's linked to it.

### Access data from within template

Let's say this is `template.php`:

```php
<p>
<?= $foo ?>
</p>
```

Data is accessible via simple variables where the variable names correspond to the assigned array keys or properties.

Yes, [shared data](#shared-data) will also be visible as variables.

### Shared data

The simplest way to share data across templates is using the `entangle()` method.

```php
$box->entangle($another);
```

Entangled boxes will share their data.

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

Note that data is __not__ shared between chained boxes by default. Each box still has its own data. (See [Shared data](#shared-data))

### Nesting

You can nest boxes by simply assigning them as data to another box.

Let's say you have some template called `layout.php` that describes the page layout like this:

```php
<html>
<head></head>
<body>
<header><?= $header ?></header>
<article><?= $article ?></article>
<footer><?= $footer ?></footer>
</body>
</html>
```

Now, you can just assign a box to each part as follows:

```php
$layout = $endobox('layout'); // create layout box

$header = $endobox('header');
$article = $endobox('article');
$footer = $endobox('footer');

// assign boxes like data
$layout->header = $header;
$layout->article = $article;
$layout->footer = $footer;

echo $layout->render(); // render whole page
```

Nothing new, really.

...And as a one-liner:

```php
echo $endobox('layout')->render([ 'header' => $endobox('header'), 'article' => $endobox('article'), 'footer' => $endobox('footer') ]);
```

### Markdown

Let's call this `template.md.php`:

```php
# Lorem ipsum
<?= $foo ?>
```

This template will first print the content of `$foo` then it is parsed as Markdown.

The same goes for Markdown _Extra_.

### Folders

```php
$endobox->add_folder('another/path/to/templates');
```

Add a template folder.

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
