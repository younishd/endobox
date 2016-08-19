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
    protected function setUp()
    {
        $this->endobox = new Factory(__DIR__ . '/resources');
    }

    public function testSimpleStaticRender()
    {
        $box = $this->endobox('hi');
        $result = $box->render();
        $this->assertSame("<h1>Hi</h1>\n", $result);
    }

    public function testSimpleDynamicRender()
    {
        $box = $this->endobox('hello');
        $box->assign([ 'subject' => 'world' ]);
        $result = $box->render();
        $this->assertSame("<h1>Hello world</h1>\n", $result);
    }

    public function testChaining()
    {
        // simple append
        $box = $this->endobox('first')->append($this->endobox('second'));
        $this->assertSame("<p>First</p>\n<p>Second</p>\n", $result);

        // simple prepend
        $box = $this->endobox('first')->prepend($this->endobox('second'));
        $this->assertSame("<p>Second</p>\n<p>First</p>\n", $result);

        // chained append
        $box = $this->endobox('first')
            ->append($this->endobox('second'))
            ->append($this->endobox('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // chained prepend
        $box = $this->endobox('first')
            ->prepend($this->endobox('second'))
            ->prepend($this->endobox('third'));
        $result = $box->render();
        $this->assertSame("<p>Third</p>\n<p>Second</p>\n<p>First</p>\n", $result);

        // __invoke()
        $box = $this->endobox('first')($this->endobox('second'))($this->endobox('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // append and prepend mixed
        $box = $this->endobox('first')
            ->append($this->endobox('second'))
            ->prepend($this->endobox('second'))
            ->append($this->endobox('first'))
            ->prepend($this->endobox('thrid'))
            ->append($this->endobox('third'));
        $result = $box->render();
        $this->assertSame(
            "<p>Third</p>\n<p>Second</p>\n<p>First</p>\n<p>Second</p>\n<p>First</p>\n<p>Third</p>\n",
            $result);
    }

    public function testAssignData()
    {
        // explicit via assign()
        $box = $this->endobox('foobar');
        $box->assign([ 'foo' => 'bar' ]);
        $result = $box->render();
        $this->assertSame("<p>bar</p>\n", $result);

        // implicit via render()
        $box = $this->endobox('foobar');
        $result = $box->render([ 'foo' => 'bar' ]);
        $this->assertSame("<p>bar</p>\n", $result);
    }

}
