# endobox

A useful toolkit for building PHP template-based dynamic web pages.

## What is a Box

A _Box_ is a data structure that allows building larger things from smaller things.
It's a kind of fancy linked list that helps you build up your template-based web page.

## Flavors

There are two main box flavors...

### `VanillaBox`

A VanillaBox is basically just the plain box structure without anything special.
What you put in comes out.

### `TemplateBox`

A _TemplateBox_ is a box that allows you to append or prepend __template files__ which will be parsed in some way
(depending on the concrete implementation of this abstract class).

#### PHP

Append or prepend PHP templates which will then get evaluated.

This box type allows data assignment via the `assign()` method.
The assigned data is accessible inside a template through the `data[]` array attribute.

> Note that `assign()` is always part of the `TemplateBox` interface, but it may have no effect depending on the actual template box type (e.g., Markdown or plain text).

#### Markdown

Append or prepend Markdown templates which will then get parsed to HTML code.

#### Plain text

Append or prepend plain text files as templates. Their content won't be touched.

#### Magic

Dynamically append or prepend PHP, Markdown, or plain text templates, as well as a combination of both PHP and Markdown.

The template type will be determined by the template file extension:
- `.md` files will be parsed as Markdown templates.
- `.php` files will be evaluated as PHP templates.
- `.md.php` files will first be evaluated as PHP, then parsed as Markdown templates.
- Anything else will be returned as is (i.e., plain text).

Of course you're able to assign data to this box which will then be accessible to all PHP templates.

That's why it's called a magic box...

## Get a Box

```php

```

## Show me the code already

```php

```

## Dependencies

- [Parsedown](https://github.com/erusev/parsedown) for Markdown templates

## License

The endobox framework is open-sourced software licensed under the [MIT license](LICENSE).
