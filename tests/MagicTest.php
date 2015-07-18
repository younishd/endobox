<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class MagicTest extends PHPUnit_Framework_TestCase {

    public function test_php()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/xyz.php');
        $box->assign('foo', '456');
        $result = $box->render();

        $expected = "123456789\n";

        $this->assertEquals($expected, $result);
    }

    public function test_markdown()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/markdown.md');
        $result = $box->render();

        $expected = "<h1>hi.</h1>\n<p>i iz a <strong>markdown</strong>.</p>";

        $this->assertEquals($expected, $result);
    }

    public function test_markdown_extra()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/markdownextra.mdx');
        $result = $box->render();

        $expected = '<h1 class="bar">Foo</h1>' . "\n"
            . '<h2 id="dolor" class="ipsum">Lorem</h2>' . "\n"
            . '<div>' . "\n"
            . '<p>This is <em>true</em> markdown text.</p>' . "\n"
            . '</div>';

        $this->assertEquals($expected, $result);
    }

    public function test_plaintext()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/template.txt');
        $result = $box->render();

        $expected = "hellowz\n"
        . "i iz a plain text.\n"
        . "lulz\n";

        $this->assertEquals($expected, $result);
    }

    public function test_php_markdown()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/fubar.md.php');
        $box->assign('foo', '<h2>hi</h2>');
        $result = $box->render();

        $expected = '<h1>hello</h1>' . "\n"
            . '<h2>hi</h2>' . "\n"
            . '<h3>lolz</h3>';

        $this->assertEquals($expected, $result);
    }

    public function test_php_markdown_extra()
    {
        $box = endobox\endobox::get()->magic();
        $box->append_template(__DIR__ . '/resources/toto.mdx.php');
        $box->assign('foo', '<h2>hi</h2>');
        $result = $box->render();

        $expected = '<h1 class="lol">hello</h1>' . "\n"
            . '<h2>hi</h2>' . "\n"
            . '<h3 id="lel">lolz</h3>';

        $this->assertEquals($expected, $result);
    }

}
