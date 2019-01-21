<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2019 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use \PHPUnit\Framework\TestCase;
use \endobox\Endobox;

class BoxTest extends TestCase
{
    private $endobox = null;

    protected function setUp()
    {
        $this->endobox = Endobox::create(__DIR__ . '/resources');
    }

    public function testSimpleStaticRender()
    {
        $box = $this->endobox->create('hi');
        $result = $box->render();
        $this->assertSame("<h1>Hi</h1>\n", $result);
    }

    public function testSimpleDynamicRender()
    {
        $box = $this->endobox->create('hello');
        $box->assign([ 'subject' => 'world' ]);
        $result = $box->render();
        $this->assertSame("<h1>Hello world</h1>\n", $result);
    }

    public function testSimpleAppend()
    {
        $box = $this->endobox->create('first')->append($this->endobox->create('second'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n", $result);
    }

    public function testSimplePrepend()
    {
        $box = $this->endobox->create('first')->prepend($this->endobox->create('second'));
        $result = $box->render();
        $this->assertSame("<p>Second</p>\n<p>First</p>\n", $result);
    }

    public function testChainedAppend()
    {
        $box = $this->endobox->create('first')
            ->append($this->endobox->create('second'))
            ->append($this->endobox->create('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);
    }

    public function testChainedPrepend()
    {
        $box = $this->endobox->create('first')
            ->prepend($this->endobox->create('second'))
            ->prepend($this->endobox->create('third'));
        $result = $box->render();
        $this->assertSame("<p>Third</p>\n<p>Second</p>\n<p>First</p>\n", $result);
    }

    public function testMixedAppendPrepend()
    {
        $box = $this->endobox->create('first')
            ->append($this->endobox->create('second'))
            ->prepend($this->endobox->create('second'))
            ->append($this->endobox->create('first'))
            ->prepend($this->endobox->create('third'))
            ->append($this->endobox->create('third'));
        $result = $box->render();
        $this->assertSame(
            "<p>Third</p>\n<p>Second</p>\n<p>First</p>\n<p>Second</p>\n<p>First</p>\n<p>Third</p>\n",
            $result);
    }

    public function testInvokeAppend()
    {
        $box = $this->endobox->create('first')($this->endobox->create('second'))($this->endobox->create('third'));
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
        $box = $this->endobox->create('foobar');
        $box->assign([ 'foo' => 'bar' ]);
        $result = $box->render();
        $this->assertSame("<p>bar</p>\n", $result);

        // implicit via render()
        $box = $this->endobox->create('foobar');
        $result = $box->render([ 'foo' => 'bar' ]);
        $this->assertSame("<p>bar</p>\n", $result);
    }

    public function testSimpleEntanglement()
    {
        $a = $this->endobox->create('foobar');
        $b = $this->endobox->create('hello');

        $a->entangle($b);

        $b->assign(['foo' => 'bar']);

        $result = $a->render();

        $this->assertSame("<p>bar</p>\n", $result);
    }

    public function testNestedEntanglement()
    {
        $a = $this->endobox->create('a');
        $b = $this->endobox->create('foobar');

        $a->entangle($b);

        $b->assign(['foo' => $a]);

        $result = $b->render();

        $this->assertSame("<p>A\n</p>\n", $result);
    }

    public function testAddFolder()
    {
        // trailing slash should not cause a problem
        $this->endobox->add_folder(__DIR__ . '/resources/eddazk/');

        $b = $this->endobox->create('empe');
        $result = $b->render();
        $this->assertSame("<p>tra8</p>\n", $result);
    }

    public function testVariableNames()
    {
        // These variables $_, $__, $___, $____ are not available, because they are internally needed for rendering.
        // Expected behavior: they cannot be assigned and are not set inside the templates.
        $result = $this->endobox->create('vars')->render([
            '_' => 1,
            '__' => 2,
            '___' => 3,
            '____' => 4
        ]);
        $this->assertSame("FALSEFALSEFALSEFALSE", $result);
    }

    public function testMarkdown()
    {
        $this->assertSame(
            "<p>The <em>quick</em> brown <strong>fox</strong> jumps over the lazy dog.</p>",
            $this->endobox->create('markdown')->render());
    }

    public function testMarkdownEval()
    {
        $this->assertSame(
            "<h1>Llewyn is the cat.</h1>",
            $this->endobox->create('llewyn')->render([ 'subject' => 'Llewyn' ]));
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
            "<h1>Hello Jim</h1>\n<p>42</p>\n<p>ANOTHER</p>\n<p>lel</p>\n<h1>Jean <em>has</em> the cat.</h1>",
            $jean->render()
        );
    }

    public function testPropertySyntaxSet()
    {
        $t = $this->endobox->create('foobar');

        // property syntax: set
        $t->foo = 'bar';

        $this->assertSame("<p>bar</p>\n", $t->render());
    }

    public function testPropertySyntaxGet()
    {
        $t = $this->endobox->create('foobar')->assign([ 'foo' => 'bar' ]);

        // property syntax: get
        $this->assertSame($t->foo, 'bar');
    }

    public function testPropertySyntaxIsset()
    {
        $t = $this->endobox->create('foobar')->assign([ 'foo' => 'bar' ]);

        // property syntax: isset
        $this->assertTrue(isset($t->foo));
        $this->assertFalse(isset($t->qux));
    }

    public function testPropertySyntaxUnset()
    {
        $t = $this->endobox->create('foobar')->assign([ 'foo' => 'bar' ]);

        unset($t->foo);

        // property syntax: isset
        $this->assertFalse(isset($t->foo));
        $this->assertFalse(isset($t->qux));
    }

    public function testNesting()
    {
        $this->assertSame("<p><h1>Hi</h1>\n</p>\n",
                $this->endobox->create('foobar')->render([ 'foo' => $this->endobox->create('hi') ]));
    }

    public function testAssignClosure()
    {
        $t = $this->endobox->create('foobar');

        $t->assign([ 'foo' => function () { return '42 is the answer.'; } ]);

        $this->assertSame("<p>42 is the answer.</p>\n", $t->render());
    }

    public function testAssignClosureWithArgs()
    {
        $t = $this->endobox->create('invoke');

        $t->assign([ 'f' => function ($a, $b, $c) { return \sprintf('a=%s, b=%s, c=%s', $a, $b, $c); } ]);

        $this->assertSame("invoking f (a=42, b=1337, c=c47f00d) as function\n", $t->render());
    }

    public function testAppendConsistency()
    {
        // alias
        $e = $this->endobox;

        $a = $e('a');
        $b = $e('b');
        $c = $e('c');
        $d = $e('d');

        $a($b)($c);

        $this->assertSame("A\nB\nC\n", $a->render());
        $this->assertSame("A\nB\nC\n", $b->render());
        $this->assertSame("A\nB\nC\n", $c->render());
        $this->assertSame("D\n", $d->render());

        $a($d);

        $this->assertSame("A\nB\nC\nD\n", $a->render());
        $this->assertSame("A\nB\nC\nD\n", $b->render());
        $this->assertSame("A\nB\nC\nD\n", $c->render());
        $this->assertSame("A\nB\nC\nD\n", $d->render());
    }

    public function testEndlessLoopDetection()
    {
        // alias
        $e = $this->endobox;

        $a = $e('a');
        $b = $e('b');
        $c = $e('c');
        $d = $e('d');

        // obviously creates endless loop
        $a($b)($c)($d)($a);

        $this->expectException(\RuntimeException::class);

        // now render should throw the exception
        $a->render();
    }

    public function testUnderflowExceptionOnGetInvalidKey()
    {
        // alias
        $e = $this->endobox;

        $b = $e('b');
        $b->foo = 'bar';

        $this->expectException(\UnderflowException::class);

        // no such key
        $x = $b->qux;
    }

    public function testClone()
    {
        // alias
        $e = $this->endobox;

        $xyzzy = $e('xyzzy');
        $a = $e('a');
        $b = $e('b');
        $c = $e('c');

        $a($xyzzy)($b);
        $xyzzy->entangle($c);

        // The point is that the data that gets directly assigned to the box will be kept.
        // However, any shared data will be lost.
        $xyzzy->assign(['x' => 'foo']);
        $c->assign(['y' => 'bar']);

        $cloneofxyzzy = clone $xyzzy;

        $this->assertSame("A\nx = foo y = barB\n", $xyzzy->render());
        $this->assertSame("x = foo y = NOT SET", $cloneofxyzzy->render());
    }

    public function testErrorHandling()
    {
        // alias
        $e = $this->endobox;

        // undefined variable
        $hello = $e('hello');

        // parse error
        $echoecho = $e('echoecho');

        $this->assertRegExp('/<h1>Hello <p>ErrorException/', $hello->render());
        $this->assertRegExp('/<p>ParseError/', $echoecho->render());
    }

    public function testStfuOperator()
    {
        // alias
        $e = $this->endobox;

        $stfu = $e('stfu');

        $this->assertSame("test\n", $stfu->render());
    }

    public function testExplicitMarkdownWrapper()
    {
        // alias
        $e = $this->endobox;

        $mark = $e('mark')->assign([
            'foo' => "Hello, _world_!"
        ]);

        $this->assertSame("<p>Hello, <em>world</em>!\n", $mark->render());
    }

}
