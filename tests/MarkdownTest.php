<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class MarkdownTest extends PHPUnit_Framework_TestCase {

    public function test_markdown_on_mdx()
    {
        $box = endobox\endobox::get()->markdown();
        $result = $box->append_template(__DIR__ . '/resources/markdownextra.mdx')->render();
        $expected = '<h1>Foo {.bar}</h1>' . "\n"
            . '<h2>Lorem {.ipsum #dolor}</h2>' . "\n"
            . '<div markdown="1">' . "\n"
            . 'This is *true* markdown text.' . "\n"
            . '</div>';
        $this->assertEquals($expected, $result);
    }

    public function test_markdown_extra_on_mdx()
    {
        $box = endobox\endobox::get()->markdownextra();
        $result = $box->append_template(__DIR__ . '/resources/markdownextra.mdx')->render();
        $expected = '<h1 class="bar">Foo</h1>' . "\n"
            . '<h2 id="dolor" class="ipsum">Lorem</h2>' . "\n"
            . '<div>' . "\n"
            . '<p>This is <em>true</em> markdown text.</p>' . "\n"
            . '</div>';
        $this->assertEquals($expected, $result);
    }

}
