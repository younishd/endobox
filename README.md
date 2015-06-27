# endobox

A useful toolkit for building PHP template-based dynamic web pages.

## Usage

### What is a Box

A _Box_ is a data structure that allows building larger things from smaller things. It's a kind of fancy linked list.

### What can I do with a Box

- Put other boxes inside a box.

- Link boxes together.

- Render the box.

### Box Flavors

- __Markdown flavored box__

This flavor allows you to parse the box content as a __Markdown template__.

- __PHP flavored box__

With a PHP flavored box you can parse __PHP templates__.

- __Template box__

A template box allows you to append template files of different types (e.g., Markdown and PHP). The type is usually determined by the file extension.

- __Vanilla box__

This box type does not alter its content.

## Demo

```php
// sample code here...
```

## Building API

### `vanilla()`

Get a vanilla box.

```php
$foo = endobox::vanilla();
```

### `with()` and `get()`

These methods allows you to get a flavored box.

The syntax is always the following:

```php
$bar = endobox::with()->...->get();
```

> Note that `...` replaces one or more chained method calls that describe the box you want to build.

#### PHP flavor

You can produce a PHP flavored box using `php()`.

```php
$bar = endobox::with()->php()->get();
```

#### Markdown flavor

You can produce a Markdown flavored box using `markdown()`.

```php
$bar = endobox::with()->markdown()->get();
```

#### Template flavor

You can enable the template flavor using the `template()` flag.

```php
$bar = endobox::with()->markdown()->php()->template()->get();
```

## Box API

Coming soon...

## License

The endobox framework is open-sourced software licensed under the [MIT license](LICENSE).
