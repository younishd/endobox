<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2016 YouniS Bensalah <younis.bensalah@gmail.com>
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
        $this->assertSame("bool(false)\nbool(false)\nbool(false)\n", $result);
    }

}
