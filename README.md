# endobox

_Simple PHP template engine._

---

## About

_endobox_ is a really simple template engine that uses native PHP as syntax.

## Highlights

- Shared data across templates
- [Nesting](#nesting), [Chaining](#chaining), [Merging](#merging)
- Native PHP syntax
- [Markdown](https://github.com/erusev/parsedown "using Parsedown") and
[Markdown Extra](https://github.com/erusev/parsedown-extra "using Parsedown Extra") support

## Usage

### Template file extensions

_endobox_ decides how to render a template according to the _file extension_:

- `.html` is rendered as a __static__ template and returned as is.

- `.php` is eval()'d as a __PHP__ template.

- `.md` (`.mdx`) is parsed as a __Markdown__ (Extra) template.

- `.md.php` (`.mdx.php`) is first eval()'d as __PHP__ template then parsed as __Markdown__ (Extra) template.

### Examples

#### _Hello world_

```php
$endobox = new endobox\Engine('path/to/templates');

$box = $endobox('hello'); // omit extension

echo $box->render([ 'subject' => 'world' ]); // assign data directly via render
```

The template `hello.php` could look like this:

```php
<h1>Hello <?php= $subject ?></h1>
```

#### _Chaining_

```php
$first = $endobox('first');
$second = $endobox('second');
$third = $endobox('third');

$first($second)($third); // chaining boxes

echo $first->render();
```

Concatenate some boxes together and print the result.

> Note that data is __not__ shared between chained boxes. (See [Merging](#merging) for more info.)

#### _Assign data_

```php
$box->assign([ 'foo' => 'bar' ]);
```

The array keys will appear as _simple variables_ inside the templates.

> __Make sure to use valid PHP variable names as keys when assigning data!__

The assigned data is _shared_ between all templates of the same box.

```php
echo $box->render([ 'qux' => 'xyz' ]); // this is also possible
```

`render()` accepts an optional data array.

#### _Accessing data from within template_

`template.php`

```php
<p>
<?php= $foo ?>
</p>
```

Data is accessible via simple variables where the variable names correspond to the assigned array keys.

#### _Nesting_

```php
$outer = $endobox('outer');
$inner = $endobox('inner');

$outer->assign([ 'foo' => $inner ]);
```

You can assign another template box like you'd do with simple data.

#### _Merging_

Merging is combining multiple boxes into one single box and make all of their data shared.

```php
$first = $endobox('first')->assign('foo' => 'bar');
$second = $endobox('second');

$first->merge($second);

echo $first->render(); // first second
```

`$first` and `$second` are now merged into one box.

Both the first and second template have now access to the assigned data.

Note that `$first` is now the merged box whereas `$second` remains unchanged.

> The key difference between _merging_ and [_chaining_](#chaining) is that the former will result in one single box
containing the combined templates with all data being shared whereas the latter will only link several boxes together
while each box remains isolated and still has its own data.

##### A shortcut...

```
$box = $endobox('first')('second'); // create box with multiple templates

$box->assign('foo' => 'bar');

echo $box->render();
```

This is equivalent to the code above.

##### Merge a chain

```
$first = $endobox('first');
$second = $endobox('second');
$third = $endobox('third');

$first($second)($third); // chaining

$first->merge(); // merging
```

Merge a chain of boxes into one box.

#### _Markdown_

`template.md.php`

```php
# Lorem ipsum
<?php= $foo ?>
```

This template will first print the content of `$foo` then everything is parsed as Markdown.

> The same goes for _Markdown Extra_.

#### _One-liner_

```php
echo (new endobox\Engine('path/to/templates'))('lorem')('ipsum')('dolor')->render([ 'sit' => 'amet' ]);
```

## License

_endobox_ is open-sourced software licensed under the [MIT license](LICENSE).
