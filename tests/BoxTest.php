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
use \endobox\Engine;

class BoxTest extends TestCase
{
    protected function setUp()
    {
        $this->engine = new Engine(__DIR__ . '/resources');
    }

    public function testSimpleStaticRender()
    {
        $box = $this->engine('hi');
        $result = $box->render();
        $this->assertSame("<h1>Hi</h1>\n", $result);
    }

    public function testSimpleDynamicRender()
    {
        $box = $this->engine('hello');
        $box->assign([ 'subject' => 'world' ]);
        $result = $box->render();
        $this->assertSame("<h1>Hello world</h1>\n", $result);
    }

    public function testChaining()
    {
        // simple append
        $box = $this->engine('first')->append($this->engine('second'));
        $this->assertSame("<p>First</p>\n<p>Second</p>\n", $result);

        // simple prepend
        $box = $this->engine('first')->prepend($this->engine('second'));
        $this->assertSame("<p>Second</p>\n<p>First</p>\n", $result);

        // chained append
        $box = $this->engine('first')
            ->append($this->engine('second'))
            ->append($this->engine('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // chained prepend
        $box = $this->engine('first')
            ->prepend($this->engine('second'))
            ->prepend($this->engine('third'));
        $result = $box->render();
        $this->assertSame("<p>Third</p>\n<p>Second</p>\n<p>First</p>\n", $result);

        // __invoke()
        $box = $this->engine('first')($this->engine('second'))($this->engine('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // append and prepend mixed
        $box = $this->engine('first')
            ->append($this->engine('second'))
            ->prepend($this->engine('second'))
            ->append($this->engine('first'))
            ->prepend($this->engine('thrid'))
            ->append($this->engine('third'));
        $result = $box->render();
        $this->assertSame(
            "<p>Third</p>\n<p>Second</p>\n<p>First</p>\n<p>Second</p>\n<p>First</p>\n<p>Third</p>\n",
            $result);
    }

    public function testAssignData()
    {
        // explicit via assign()
        $box = $this->engine('foobar');
        $box->assign([ 'foo' => 'bar' ]);
        $result = $box->render();
        $this->assertSame("<p>bar</p>\n", $result);

        // implicit via render()
        $box = $this->engine('foobar');
        $result = $box->render([ 'foo' => 'bar' ]);
        $this->assertSame("<p>bar</p>\n", $result);
    }

}
