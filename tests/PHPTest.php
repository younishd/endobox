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

    public function test_render_static_template()
    {
        $box = endobox\endobox::get()->php();
        $box->append_template(__DIR__ . '/resources/simple.php');
        $expected = "<h1>Should count from 0 to 9</h1>\n"
            . "<ul>\n<li>0</li>\n<li>1</li>\n<li>2</li>\n<li>3</li>\n<li>4</li>\n<li>5</li>\n<li>6</li>\n<li>7</li>\n"
            . "<li>8</li>\n<li>9</li>\n</ul>";
        $this->assertEquals($expected, trim($box->render()));
    }

    public function test_render_dynamic_template()
    {
        $box = endobox\endobox::get()->php();
        $box->append_template(__DIR__ . '/resources/whatever.php');
        $box->assign('foo', 'LOLZ');
        $box->assign(['bar' => 'LUL']);
        $expected = "<h1>lel</h1>\n"
            . "<h2>LOLZ</h2>\n"
            . "<h3>LUL</h3>";
        $this->assertEquals($expected, trim($box->render()));
    }

}
