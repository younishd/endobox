<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class PHPTest extends PHPUnit_Framework_TestCase {

    public function test_render()
    {
        $box = endobox\endobox::get()->php();
        $box->append_template(__DIR__ . '/resources/simple.php');
        $expected = "<h1>Should count from 0 to 9</h1>\n"
            . "<ul>\n<li>0</li>\n<li>1</li>\n<li>2</li>\n<li>3</li>\n<li>4</li>\n<li>5</li>\n<li>6</li>\n<li>7</li>\n"
            . "<li>8</li>\n<li>9</li>\n</ul>\n";
        $this->assertEquals($expected, $box->render());
    }

    public function test_render_with_assign()
    {
        $box = endobox\endobox::get()->php();
        $box->append_template(__DIR__ . '/resources/whatever.php');
        $box->assign('foo', 'LOLZ');
        $box->assign(['bar' => 'LUL']);
        $expected = "<h1>lel</h1>\n"
            . "<h2>LOLZ</h2>\n"
            . "<h3>LUL</h3>\n";
        $this->assertEquals($expected, $box->render());
    }

    public function test_render_nested()
    {
        $box = endobox\endobox::get()->php();
        $nested = endobox\endobox::get()->php();
        $box->append_template(__DIR__ . '/resources/xyz.php');
        $nested->append_template(__DIR__ . '/resources/xyz.php');
        $box->assign('foo', $nested);
        $nested->assign('foo', '456');
        $expected = "123123456789\n789\n";
        $this->assertEquals($expected, $box->render());
    }

    public function test_chaining()
    {
        $result = endobox\endobox::get()->php()

            ->append_template(__DIR__ . '/resources/one.php')
            ->append_template(__DIR__ . '/resources/two.php')
            ->append_template(__DIR__ . '/resources/three.php')
            ->append_template(__DIR__ . '/resources/four.php')

            ->assign('two', 'Two')
            ->assign('four', 'Four')

            ->render();

        $expected = 'OneTwoThreeFour';

        $this->assertEquals($expected, $result);
    }

    public function test_endless()
    {
        //
    }

}
