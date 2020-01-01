![endobox](endobox.png "made with ♥")

# ENDOBOX

__clean af template engine.__

[![Build Status](https://travis-ci.org/younishd/endobox.svg?branch=master)](https://travis-ci.org/younishd/endobox)
[![Code Climate](https://codeclimate.com/github/younishd/endobox/badges/gpa.svg)](https://codeclimate.com/github/younishd/endobox)
[![Latest Stable Version](https://poser.pugx.org/younishd/endobox/version)](https://packagist.org/packages/younishd/endobox)
[![Total Downloads](https://poser.pugx.org/younishd/endobox/downloads)](https://packagist.org/packages/younishd/endobox)
[![License](https://poser.pugx.org/younishd/endobox/license)](https://packagist.org/packages/younishd/endobox)

![endobox](code.png "made with ♥")

| :seedling: Native PHP syntax | :pencil: Markdown on-board | :rocket: Efficient API |
| :---: | :---: | :---: |
| Write templates in vanilla PHP. No need to learn a new syntax. | A full-blown Markdown parser is built right in. Yes, it can be combined with PHP! | Do powerful things with just a handful of elementary methods. |

---

## Documentation

- [Installation](#installation)
- [Get started](#get-started)
- [Render templates](#render-templates)
- [File extensions](#file-extensions)
- [Data](#data)
- [Chaining & Nesting](#chaining--nesting)
- [Functions](#functions)
- [Cloning](#cloning)

### Installation

The recommended way to install ENDOBOX is via [Composer](https://getcomposer.org):

```bash
composer require younishd/endobox
```

You will need at least __PHP 7.0.0__.

### Get started

The typical way to configure ENDOBOX to load templates for an application looks like this:

```php
require_once '/path/to/vendor/autoload.php';

$endobox = \endobox\Endobox::create('path/to/templates');

$endobox->addFolder('another/path/to/templates'); // optional
```

### Render templates

The first thing you want to do is instantiate a template `Box` as follows:

```php
$welcome = $endobox('welcome');
```

To render the template with some variables call the `render()` method:

```php
echo $welcome->render([ 'name' => "Alice" ]);
```

The template file itself could look like this:

###### `welcome.php`

```
<h1>Hello, <?= $name ?>!</h1>
```

### File extensions

ENDOBOX decides how to render a template based on the __file extension__.

When you instantiate the template `Box` however, the extension is omitted.

```php
$members = $endobox('members'); // no file extension
```

#### PHP: `.php`

PHP templates are processed by evaluating the code between PHP tags (i.e., `<? … ?>`) and returning the result.


###### `members.php`

```
<h1>Members</h1>
<ul>
    <?php foreach ($users as $u): ?>
        <li><?= $u->name ?></li>
    <?php endforeach ?>
</ul>
```

> :information_source: __Protip:__ The `<?=` is syntactic sugar for `<?php echo`.

#### Markdown: `.md`

Markdown templates are processed by a Markdown parser ([Parsedown](https://github.com/erusev/parsedown)) which produces the corresponding HTML code. This can be used for static content.

###### `members.md`

```markdown
# Members

- Alice
- Bob
- Carol
```

#### PHP+Markdown: `.md.php`

As the name suggests, this template type combines both PHP and Markdown: The template gets evaluated as PHP first, then parsed as Markdown. Pretty neat.

###### `members.md.php`

```
# Members

<?php foreach ($users as $u): ?>
    - <?= $u->name ?>
<?php endforeach ?>
```

#### HTML: `.html`

HTML templates are always printed as is. No further processing takes place.

###### `members.html`

```html
<h1>Members</h1>
<ul>
    <li>Alice</li>
    <li>Bob</li>
    <li>Carol</li>
</ul>
```

### Data

Data is accessible inside a template as simple __variables__ (e.g., `$foo`) where the variable name corresponds to the assigned array key or property.

```
<h1>Hello, <?= $username ?>!</h1>
```

#### Assign data

There are several ways to assign data to a template box:

```php
// via assign(…)
$welcome->assign([ "username" => "eve" ]);

// via object property
$welcome->username = "eve";

// via render(…)
$welcome->render([ "username" => "eve" ]);
```

Notice that `assign()` and `render()` both receive an `array` as argument.

#### Shared data

Usually, template boxes are isolated from each other. Data that's been assigned to one box, will not be visible from another.

```php
$welcome->username = "eve";          // not accessible to 'profile'
$profile->email = "eve@example.com"; // not accessible to 'welcome'
```

If they should share their data however, you can __link__ them together:

```php
$welcome->link($profile);
```

Now, these template boxes are linked and they share the same data.

###### `welcome.php`

```
<h1>Hello, <?= $username ?>!</h1>
<p>Your email address is: <code><?= $email ?></code></p>
```

###### `profile.php`

```
<h1>Profile</h1>
<ul>
    <li>Username: <strong><?= $username ?></strong></li>
    <li>Email: <strong><?= $email ?></strong></li>
</ul>
```

Notice how `welcome.php` prints out `$email` which was initially assigned to `$profile` and `profile.php` echoes `$username` even though it was assigned to `$welcome`.

> :information_source: __Protip:__ You can create template boxes using an existing `Box` object (instead of using the `BoxFactory` object) with `$box->create('template')` which has the advantage of __linking the two boxes__ together by default.

#### Escaping

Escaping is a form of data filtering which sanitizes unsafe, user supplied input prior to outputting it as HTML.

ENDOBOX provides two shortcuts to the `htmlspecialchars()` function: `$escape()` and its shorthand version `$e()`

```
<h1>Hello, <?= $escape($username) ?>!</h1>

<h1>Hello, <?= $e($username) ?>!</h1>
```

##### Escaping HTML attributes

> :warning: __Warning:__ It's VERY important to always double quote HTML attributes that contain escaped variables, otherwise your template will still be open to injection attacks (e.g., [XSS](https://www.owasp.org/index.php/Cross-site_Scripting_(XSS))).

```
<!-- Good -->
<img src="portrait.jpg" alt="<?= $e($name) ?>">

<!-- BAD -->
<img src="portrait.jpg" alt='<?= $e($name) ?>'>

<!-- BAD -->
<img src="portrait.jpg" alt=<?= $e($name) ?>>
```

### Chaining & Nesting

Since you're rarely dealing with just a single template you might be looking for a method that combines multiple templates in a meaningful way.

#### Chaining

By __chaining__ we mean concatenating templates without rendering them.

Chaining two templates is as simple as:

```php
$header($article);
```

Now, calling `->render()` on either `$header` or `$article` will render both templates and return the concatenated result.

> :information_source: __Protip:__ The benefit of not having to render the templates to strings right away is _flexibility_: You can define the layout made out of your templates before knowing the concrete values of their variables.

The general syntax for chaining a bunch of templates is simply:

```php
$first($second)($third)($fourth); // and so on
```

Neat.

The more explicit (and strictly equivalent) form would be using `append()` or `prepend()` as follows:

```php
$first->append($second)->append($third)->append($fourth);
```

Or…

```php
$fourth->prepend($third)->prepend($second)->prepend($first);
```

> :information_source: __Protip:__ Note that the previously seen `Box::__invoke()` is simply an alias of `Box::append()`.


You have now seen how you can append (or prepend) `Box`es together.

Notice however that the variables `$first`, `$second`, `$third`, and `$fourth` were objects of type `Box` which means they needed to be brought to life at some point —
probably using the `BoxFactory` object created in the very beginning (which we kept calling `$endobox` in this document).

In other words the complete code would probably look something like this:

```php
$first = $endobox('first');
$second = $endobox('second');
$third = $endobox('third');

echo $first($second)($third);
```

It turns out there is a way to avoid this kind of boilerplate code: You can directly pass the name of the template (a `string`) to the `append()` method instead of the `Box` object!

So, instead you could just write:

```php
echo $endobox('first')('second')('third');
```

It looks trivial, but there is a lot going on here. The more verbose form would look as follows:

```php
echo $endobox->create('first')->append('second')->append('third');
```

This is – in turn – equivalent to the following lines:

```php
echo ($_ = $endobox->create('first'))
        ->append($endobox->create('second')->link($_))
        ->append($endobox->create('third')->link($_));
```

Notice that unlike before these (implicitly created) boxes are now all __linked__ together automatically, meaning they share the same data.

The rule of thumb is: _`Box`es created from other `Box`es are linked by default._

#### Nesting

A fairly different approach (probably the _template designer_ rather than the _developer_ way) would be to define some sort of __layout template__ instead:

###### `layout.php`

```
<html>
<head></head>
<body>
<header><?= $header ?></header>
<article><?= $article ?></article>
<footer><?= $footer ?></footer>
```

Then somewhere in controller land:

```php
$layout = $endobox('layout');
$header = $endobox('header');   // header.html
$article = $endobox('article'); // article.php
$footer = $endobox('footer');   // footer.html

echo $layout->render([
    'header' => $header,
    'article' => $article->assign([ 'title' => "How to make Lasagna" ]),
    'footer' => $footer
]);
```

This should be fine, but we can get rid of some boilerplate code here: `$header` and `$footer` really don't need to be variables.

That's where __nesting__ comes into play!

Use the `$box()` function to instantiate a template `Box` from _inside_ another template:

###### `layout.php`

```
<html>
<head></head>
<body>
<header><?= $box('header') ?></header>
<article><?= $article ?></article>
<footer><?= $box('footer') ?></footer>
```

Then simply…

```php
echo $endobox('layout')->render([
    'article' => $endobox('article')->assign([ 'title' => "How to make Lasagna" ])
]);
```

This is already much cleaner, but it gets even better: By using `$box()` to nest a template `Box` inside another these two boxes will be __linked__ by default!

That allows us to condense this even further. Check it out:

###### `layout.php`

```
<html>
<head></head>
<body>
<header><?= $box('header') ?></header>
<article><?= $box('article') ?></article>
<footer><?= $box('footer') ?></footer>
```

All three templates are now nested using `$box()` and therefore linked to their parent (i.e., `$layout`).

This reduces our controller code to one line:

```php
echo $endobox('layout')->render([ 'title' => "How to make Lasagna" ]);
```

Notice how we are assigning a title to the `layout` template even though the actual `$title` variable occurs in the nested `article` template.

> :information_source: __Protip:__ The `$box()` function is also available as a method of `Box` objects (i.e., outside templates): You can instantiate new boxes with `$box->create('template')` where `$box` is some `Box` object that has already been created.

### Functions

Functions are a cool and handy way of adding reusable functionality to your templates (e.g., filters, URL builders…).

#### Registering functions

You can register your own custom function (i.e., [closure](https://www.php.net/manual/en/functions.anonymous.php)) by simply assigning it to a template `Box` __just like data!__

Here is a simple function `$day()` which returns the day of the week:

```php
$calendar->day = function () { return date('l'); };
```

Inside your template file you can then use it in the same fashion as any variable:

```
<p>Today is <?= $day ?>.</p>
```

This would look like this (at least sometimes):

```html
<p>Today is Tuesday.</p>
```

You can go even further and actually invoke the variable just like any function and actually __pass some arguments__ along the way.

Below is a simple closure `$a()` that wraps and escapes some text in a hyperlink tag:

```php
$profile->a = function ($text, $href) {
    return sprintf('<a href="%s">%s</a>',
            htmlspecialchars($href),
            htmlspecialchars($text));
};
```

Calling this function inside your template would look like this:

```
<p>Follow me on <?= $a("GitHub", "https://github.com/younishd") ?></p>
```

This would produce something like this:

```html
<p>Follow me on <a href="https://github.com/younishd">GitHub</a></p>
```

#### Default functions

There are a couple of default helper functions that you can use out of the box (some of which you may have already seen):

|Function|Description|Example|
|--|--|--|
|`$box()` or `$b()`|Instantiate a `Box` from within another template. (See [Nesting](#chaining--nesting).)|`<article><?= $box('article') ?></article>`|
|`$markdown()` or `$m()`|Render some text as Markdown. Useful when the text is user input/stored in a database.|`<?= $markdown('This is some _crazy comment_!') ?>`|
|`$escape()` or `$e()`|Sanitize unsafe user input using `htmlspecialchars()`. (See [Escaping](#escaping).)|`<img src="portrait.jpg" alt="<?= $e($name) ?>">`|

### Cloning

You can easily clone a template `Box` using the built-in `clone` keyword.

```php
$sheep = $endobox('sheep');

$cloned = clone $sheep;
```

The cloned box will have the same content and data as the original one. However, chained or linked boxes are discarded.


## License

_ENDOBOX_ is open-sourced software licensed under the [MIT license](LICENSE).
