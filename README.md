# endobox

_Simple PHP template engine._

---

## About

_endobox_ is a really simple template engine that uses native PHP as syntax.

## Usage

### _Hello world_

```php
$endobox = new endobox\endobox('path/to/templates');

$box = $endobox('hello'); // omit extension

echo $box->render();
```

Simply print the content of `hello.html`.

### _Chaining boxes_

```php
$box = $endobox('first')('second')('third');

$box('fourth')('fifth');

$box->render();
```

Concatenate some template boxes together and print the result.

### _Assign data_

```php
$box->assign([ 'foo' => 'bar' ]);
```

The assigned data is shared between all chained or nested template boxes.

### _Accessing data from within template_

```php
<p>
<?php= $foo ?>
</p>
```

Data is accessible via simple variables where the variable names correspond to the assigned array keys.

### _Nesting_

```php
$box = $endobox('first');
$another = $endobox('second');

$box->assign([ 'foo' => $another ]);
```

You can assign another template box like you'd do with simple data.

### _Markdown_

```php
# Lorem ipsum
<?php= $foo ?>
```

This template will first print the content of `$foo` then everything is parsed as Markdown.

### _Template file extensions_

- `template.html` plain text

- `template.md` parse as Markdown

- `template.mdx` parse as Markdown Extra

- `template.php` eval() as PHP

- `template.md.php` eval() as PHP then parse as Markdown

- `template.mdx.php` eval() as PHP then parse as Markdown Extra

## Highlights

- Nesting and chaining
- Shared data across templates
- Native PHP syntax
- [Markdown](https://github.com/erusev/parsedown "using Parsedown") and
[Markdown Extra](https://github.com/erusev/parsedown-extra "using Parsedown Extra") support

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
