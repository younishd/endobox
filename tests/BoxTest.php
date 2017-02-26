<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2017 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use \PHPUnit\Framework\TestCase;
use \endobox\Factory;

class BoxTest extends TestCase
{
    private $endobox = null;

    protected function setUp()
    {
        $this->endobox = new Factory(__DIR__ . '/resources');
    }

    public function testSimpleStaticRender()
    {
        $box = $this->endobox->make('hi');
        $result = $box->render();
        $this->assertSame("<h1>Hi</h1>\n", $result);
    }

    public function testSimpleDynamicRender()
    {
        $box = $this->endobox->make('hello');
        $box->assign([ 'subject' => 'world' ]);
        $result = $box->render();
        $this->assertSame("<h1>Hello world</h1>\n", $result);
    }

    public function testSimpleAppend()
    {
        $box = $this->endobox->make('first')->append($this->endobox->make('second'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n", $result);
    }

    public function testSimplePrepend()
    {
        $box = $this->endobox->make('first')->prepend($this->endobox->make('second'));
        $result = $box->render();
        $this->assertSame("<p>Second</p>\n<p>First</p>\n", $result);
    }

    public function testChainedAppend()
    {
        $box = $this->endobox->make('first')
            ->append($this->endobox->make('second'))
            ->append($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);
    }

    public function testChainedPrepend()
    {
        $box = $this->endobox->make('first')
            ->prepend($this->endobox->make('second'))
            ->prepend($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>Third</p>\n<p>Second</p>\n<p>First</p>\n", $result);
    }

    public function testMixedAppendPrepend()
    {
        $box = $this->endobox->make('first')
            ->append($this->endobox->make('second'))
            ->prepend($this->endobox->make('second'))
            ->append($this->endobox->make('first'))
            ->prepend($this->endobox->make('third'))
            ->append($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame(
            "<p>Third</p>\n<p>Second</p>\n<p>First</p>\n<p>Second</p>\n<p>First</p>\n<p>Third</p>\n",
            $result);
    }

    public function testInvokeAppend()
    {
        $box = $this->endobox->make('first')($this->endobox->make('second'))($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);
    }

    public function testInvokeMake()
    {
        // alias
        $e = $this->endobox;

        // make using __invoke shortcut
        $result = $e('first')->render();

        $this->assertSame("<p>First</p>\n", $result);
    }

    public function testAssignData()
    {
        // explicit via assign()
        $box = $this->endobox->make('foobar');
        $box->assign([ 'foo' => 'bar' ]);
        $result = $box->render();
        $this->assertSame("<p>bar</p>\n", $result);

        // implicit via render()
        $box = $this->endobox->make('foobar');
        $result = $box->render([ 'foo' => 'bar' ]);
        $this->assertSame("<p>bar</p>\n", $result);
    }

    public function testSimpleEntanglement()
    {
        $a = $this->endobox->make('foobar');
        $b = $this->endobox->make('hello');

        $a->entangle($b);

        $b->assign(['foo' => 'bar']);

        $result = $a->render();

        $this->assertSame("<p>bar</p>\n", $result);
    }

    public function testAddFolder()
    {
        // trailing slash should not cause a problem
        $this->endobox->add_folder(__DIR__ . '/resources/eddazk/');

        $b = $this->endobox->make('empe');
        $result = $b->render();
        $this->assertSame("<p>tra8</p>\n", $result);
    }

    public function testVariableNames()
    {
        // these variables $_ $__ $___ are not available because they are internally needed for rendering
        // expected behavior: they cannot be assigned and are not set inside the templates
        $result = $this->endobox->make('vars')->render([ '_' => 1, '__' => 2, '___' => 3 ]);
        // all three isset checks return false
        $this->assertSame("FALSEFALSEFALSE", $result);
    }

    public function testMarkdown()
    {
        $this->assertSame(
            "<p>The <em>quick</em> brown <strong>fox</strong> jumps over the lazy dog.</p>",
            $this->endobox->make('markdown')->render());
    }

    public function testMarkdownExtra()
    {
        $this->assertSame(
            "<div>\n<p>The <em>quick</em> brown <strong>fox</strong> jumps over the lazy dog.</p>\n</div>",
            $this->endobox->make('markdownextra')->render());
    }

    public function testMarkdownEval()
    {
        $this->assertSame(
            "<h1>Llewyn is the cat.</h1>",
            $this->endobox->make('llewyn')->render([ 'subject' => 'Llewyn' ]));
    }

    public function testMarkdownExtraEval()
    {
        $this->assertSame(
            "<div>\n<h1>Llewyn <em>has</em> the cat.</h1>\n</div>",
            $this->endobox->make('jean')->render([ 'subject' => 'Llewyn' ]));
    }

    public function testMixItAllTogether()
    {
        // alias
        $e = $this->endobox;

        // make some boxes
        $jean = $e('jean');
        $foobar = $e('foobar');
        $hello = $e('hello');
        $first = $e('first');
        $another = $e('another');

        // chaining
        $hello($another)($jean);

        // entanglement
        $jean->entangle($foobar);
        $another->entangle($first);

        // assign
        $foobar->assign([ 'foo' => 'bar', 'subject' => 'Jean' ]);
        $first->assign([ 'yet' => 42, 'another' => 'ANOTHER', 'one' => 'lel' ]);
        $hello->assign([ 'subject' => 'Jim' ]);

        // render
        $this->assertSame(
            "<h1>Hello Jim</h1>\n<p>42</p>\n<p>ANOTHER</p>\n<p>lel</p>\n<div>\n<h1>Jean <em>has</em> the cat.</h1>\n</div>",
            $jean->render()
        );
    }

    public function testPropertySyntaxSet()
    {
        $t = $this->endobox->make('foobar');

        // property syntax: set
        $t->foo = 'bar';

        $this->assertSame("<p>bar</p>\n", $t->render());
    }

    public function testPropertySyntaxGet()
    {
        $t = $this->endobox->make('foobar')->assign([ 'foo' => 'bar' ]);

        // property syntax: get
        $this->assertSame($t->foo, 'bar');
    }

    public function testPropertySyntaxIsset()
    {
        $t = $this->endobox->make('foobar')->assign([ 'foo' => 'bar' ]);

        // property syntax: isset
        $this->assertTrue(isset($t->foo));
        $this->assertFalse(isset($t->qux));
    }

    public function testPropertySyntaxUnset()
    {
        $t = $this->endobox->make('foobar')->assign([ 'foo' => 'bar' ]);

        unset($t->foo);

        // property syntax: isset
        $this->assertFalse(isset($t->foo));
        $this->assertFalse(isset($t->qux));
    }

    public function testNesting()
    {
        $this->assertSame("<p><h1>Hi</h1>\n</p>\n",
                $this->endobox->make('foobar')->render([ 'foo' => $this->endobox->make('hi') ]));
    }

}
