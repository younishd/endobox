# endobox

A useful toolkit for building PHP template-based dynamic web pages.

## What the hell is a Box

A _Box_ is a data structure that allows building larger things from smaller things.
It's a kind of fancy linked list that helps you build up your template-based web page.

## Box Types

There are three types of boxes: `Box`, `VanillaBox`, and `TemplateBox`.

First of all you should know that `Box` is base class of `VanillaBox` which is in turn base class of `TemplateBox`.

### `Box`

A `Box` is the most general type. It is basically just the plain structure without anything template-related.

> Also note that `Box` itself is an abstract class.

#### API

Append/prepend a box.

```
append( Box $b ) : Box
prepend( Box $b ) : Box
```

Render everything and return the code.

```
render() : string
```

Basic linked list getters: _next_, _previous_, _head_, and _tail_.

```
next() : Box
prev() : Box
head() : Box
tail() : Box
```

### `VanillaBox`

A `VanillaBox` allows you to append or prepend plain text files as templates.

This class is used either to add plain text templates or as a base class for more complex template boxes
(e.g., Markdown) that fire the content through a parser before returning it.

#### API

Append/prepend a template file.

```
append_template( string $t ) : Box
prepend_template( string $t ) : Box
```

### `TemplateBox`

A `TemplateBox` lets you append or prepend template files and assign data to the box.

#### API

Assign some data.

You can either assign a key-value couple or pass a whole array as key argument (and omit the value).

```
assign( $key [, $value = null] ) : Box
```

> The main idea of all this is that you should only worry about the public API of these three classes.

## Flavors

Flavors are settings that control the __behavior__ of the box instance you want to create.

> Note that the public __API__ of a box is _always_ dictated by one of the three
[box types](#box-types) discussed above.
So in practice, all you need to know about a flavor is _what does it do?_ and _what box type will I get?_

### PHP

- Box type: [`TemplateBox`](#templatebox)

Append or prepend PHP templates which will then get evaluated.

This flavor can be combined with the [`endless`](#endless) flag. When enabled,
the code will get evaluated as long as there are opening `<?php` tags left.

### Markdown

- Box type: [`VanillaBox`](#vanillabox)

Append or prepend Markdown templates which will then get parsed to HTML code.

### Plain text

- Box type: [`VanillaBox`](#vanillabox)

Append or prepend plain text files. Their content won't be touched.

### Magic

- Box type: [`TemplateBox`](#vanillabox)

Dynamically append or prepend PHP, Markdown, or plain text templates, as well as a combination of both PHP and Markdown.

The template type will be determined by the template file extension:
- `.md` files will be parsed as __Markdown__ templates.
- `.mdx` files will be parsed as __Markdown Extra__ templates.
- `.php` files will be evaluated as __PHP__ templates.
- `.md.php` files will first be evaluated as __PHP__, then parsed as __Markdown__ templates.
- `.mdx.php` files will first be evaluated as __PHP__, then parsed as __Markdown__ __Extra__ templates.
- Anything else will be returned as is (i.e., __plain text__).

This flavor can be combined with the [`endless`](#endless) flag. When enabled,
the PHP code parts will get evaluated as long as there are opening `<?php` tags left.

Of course you're able to assign data to this box which will then be accessible to all PHP templates.
That's why it's magic...

### Endless

This flag allows parsing some box content multiple times (usually until there is nothing left to parse).

__Use with caution!__ Especially, do not enable this at a level where user input is involved.

## Get a Box

Now you probably want to know how to get all these boxes. Here you go:

```php
// Get a PHP box
$b = endobox\endobox::get()->php();
```

```php
// Get a Markdown box
$b = endobox\endobox::get()->markdown();
```

```php
// Get a plain text box
$b = endobox\endobox::get()->vanilla();
```

```php
// Get a magic box
$b = endobox\endobox::get()->magic();
```

The same shit with the __endless__ flag set:

```php
// Get an endless PHP box
$b = endobox\endobox::get()->endless()->php();
```

```php
// Get an endless magic box
$b = endobox\endobox::get()->endless()->magic();
```

With __Markdown Extra__:

```php
// Get a Markdown Extra box
$b = endobox\endobox::get()->markdownextra();
```

### Functional way

The same using functions:

```php
// Get a PHP box
$b = endobox\php();
```

```php
// Get a Markdown box
$b = endobox\markdown();
```

```php
// Get a plain text box
$b = endobox\vanilla();
```

```php
// Get a magic box
$b = endobox\magic();
```

With __endless__ flag:

```php
// Get an endless PHP box
$b = endobox\php_e();
```

```php
// Get an endless magic box
$b = endobox\magic_e();
```

With __Markdown Extra__:

```php
// Get a Markdown Extra box
$b = endobox\markdownextra();
```

## Dependencies

- [parsedown](https://github.com/erusev/parsedown)
- [parsedown-extra](https://github.com/erusev/parsedown)

## Contributors

Thanks to [fabienwang](https://github.com/fabienwang) for testing and improvements.

## License

The endobox framework is open-sourced software licensed under the [MIT license](LICENSE).
