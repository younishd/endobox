# endobox

Good things come in small boxes. :package:

## What is a _Box_

A _Box_ is a data structure that allows building larger things from smaller things.

You can think of it as a fancy linked list.

## What are _Renderable_ objects

A _Renderable_ object implements a `render()` method returning some text.

Note that a _Box_ is also a _Renderable_ object itself.

(Things will become clear. Just keep that in mind for now.)

## What can I do with a _Box_

- Put _Renderable_ objects into the _Box_.

- Link _Boxes_ together.

- Render the _Box_.

### Put _Renderable_ objects into the _Box_

A _Box_ has an __inner linked list__ of _Renderable_ objects.

You may __append__ or __prepend__ any _Renderable_ to this __inner list__.

```php
append_inner( Renderable $r )
```
```php
prepend_inner( Renderable $r )
```

Note that these two methods are protected and _not_ public, so you have to call them using the `load()` [callback method](#callback-methods).

### Link _Boxes_ together

A _Box_ represents a __linked list element__ itself. It keeps track of its __previous__ and __next__ _Box_ object in the __outer list__.

You may __append__ or __prepend__ any _Box_ to this __outer list__.

```php
append( Box $b )
```
```php
prepend( Box $b )
```

### Render the _Box_

The point of all this linked list magic should become clear now...

When you __render__ a _Box_ (i.e., call the `render()` method on a _Box_ instance), the following things will happen:

- Loop through all _Boxes_ of the __outer list__.

- Render each _Box_ separately calling `render_inner()`.

- Concatenate the resulting codes and return that.

#### Inner rendering

Now each _Box_ will be rendered separately by calling `render_inner()` as it says in the 2nd step. This simply means the following:

- Call the `load()` callback method.

- Loop through all _Renderables_ of the __inner list__.

- Render each _Renderable_.

- Concatenate the resulting codes, give it to the `build()` callback method and return that.

## Callback methods

### load

The `load()` callback method gets executed right __before__ the [inner rendering](#inner-rendering) starts.

It is where you normally append or prepend the inner _Renderable_ objects to this box using `append_inner()` or `prepend_inner()`.

Note that the default `load()` method does nothing.

### build

The `build()` callback method gets executed right __after__ the [inner rendering](#inner-rendering) finishes. It receives the rendered code as argument and is supposed to return it in some way or another.

This allows you to alter the rendered inner code before finally returning it (e.g., implementing some kind of parser or wrapper Box).

Note that the default `build()` method just returns the code argument as is.

## Usage

Just extend the `Box` base class and override the `load()` and `build()` callback methods.

_More documentation coming soon..._
