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

class BoxTest extends TestCase
{

    public function testSimpleRender()
    {
        $endobox = new \endobox\endobox(__DIR__ . '/resources');
        $box = $endobox('hello');
        $result = $box->render();
        $this->assertSame("<h1>Hello</h1>\n", $result);
    }

    public function testChaining()
    {
        $endobox = new \endobox\endobox(__DIR__ . '/resources');
        $box = $endobox('first')('second')('third');
        $result = $box->render();
        $this->assertSame("<p>First</p>\n<p>Second</p>\n<p>Third</p>\n", $result);
    }

    public function testAssignData()
    {
        $endobox = new \endobox\endobox(__DIR__ . '/resources');
        $box = $endobox('foobar');
        $box->assign([ 'foo' => 'bar' ]);
        $result = $box->render();
        $this->assertSame("<p>bar</p>\n", $result);
    }

}
