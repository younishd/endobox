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

    public function testChaining()
    {
        // simple append
        $box = $this->endobox->make('first')->append($this->endobox->make('second'));
        $this->assertSame("<p>First</p>\n<p>Second</p>\n", $result);

        // simple prepend
        $box = $this->endobox->make('first')->prepend($this->endobox->make('second'));
        $this->assertSame("<p>Second</p>\n<p>First</p>\n", $result);

        // chained append
        $box = $this->endobox->make('first')
            ->append($this->endobox->make('second'))
            ->append($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // chained prepend
        $box = $this->endobox->make('first')
            ->prepend($this->endobox->make('second'))
            ->prepend($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>Third</p>\n<p>Second</p>\n<p>First</p>\n", $result);

        // __invoke()
        $box = $this->endobox->make('first')($this->endobox->make('second'))($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);

        // append and prepend mixed
        $box = $this->endobox->make('first')
            ->append($this->endobox->make('second'))
            ->prepend($this->endobox->make('second'))
            ->append($this->endobox->make('first'))
            ->prepend($this->endobox->make('thrid'))
            ->append($this->endobox->make('third'));
        $result = $box->render();
        $this->assertSame(
            "<p>Third</p>\n<p>Second</p>\n<p>First</p>\n<p>Second</p>\n<p>First</p>\n<p>Third</p>\n",
            $result);
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

}
