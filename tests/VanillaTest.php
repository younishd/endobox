<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class VanillaTest extends PHPUnit_Framework_TestCase {

    public function test_empty()
    {
        $box = endobox\endobox::get()->vanilla();
        $this->assertNull($box->next());
        $this->assertNull($box->prev());
    }

    public function test_append()
    {
        $first = endobox\endobox::get()->vanilla();
        $second = endobox\endobox::get()->vanilla();
        $third = endobox\endobox::get()->vanilla();

        $result = $first->append($second);

        $this->assertEquals($second, $first->next());
        $this->assertEquals($first, $second->prev());
        $this->assertNull($first->prev());
        $this->assertNull($second->next());
        $this->assertEquals($first, $result);

        $result = $first->append($third);

        $this->assertEquals($second, $first->next());
        $this->assertEquals($first, $second->prev());
        $this->assertEquals($third, $second->next());
        $this->assertEquals($second, $third->prev());
        $this->assertNull($first->prev());
        $this->assertNull($third->next());
        $this->assertEquals($second, $result);
    }

    public function test_prepend()
    {
        $first = endobox\endobox::get()->vanilla();
        $second = endobox\endobox::get()->vanilla();
        $third = endobox\endobox::get()->vanilla();

        $result = $first->prepend($second);

        $this->assertEquals($second, $first->prev());
        $this->assertEquals($first, $second->next());
        $this->assertNull($first->next());
        $this->assertNull($second->prev());
        $this->assertEquals($first, $result);

        $result = $first->prepend($third);

        $this->assertEquals($second, $first->prev());
        $this->assertEquals($first, $second->next());
        $this->assertEquals($third, $second->prev());
        $this->assertEquals($second, $third->next());
        $this->assertNull($first->next());
        $this->assertNull($third->prev());
        $this->assertEquals($second, $result);
    }

    public function test_render()
    {
        $box = endobox\endobox::get()->vanilla();

        $first = $this->getMockBuilder('endobox\core\VanillaBox')
            ->setMethods(['render_inner'])
            ->getMock();

        $second = $this->getMockBuilder('endobox\core\VanillaBox')
            ->setMethods(['render_inner'])
            ->getMock();

        $third = $this->getMockBuilder('endobox\core\VanillaBox')
            ->setMethods(['render_inner'])
            ->getMock();

        $first->method('render_inner')->willReturn('First');
        $second->method('render_inner')->willReturn('Second');
        $third->method('render_inner')->willReturn('Third');

        $box->append($first)->append($second)->append($third);

        $expected = 'FirstSecondThird';

        $this->assertEquals($expected, $box->render());
        $this->assertEquals($expected, $first->render());
        $this->assertEquals($expected, $second->render());
        $this->assertEquals($expected, $third->render());
    }

    public function test_render_empty()
    {
        $box = endobox\endobox::get()->vanilla();
        $this->assertSame('', $box->render());
    }

    public function test_append_template()
    {
        $box = endobox\endobox::get()->vanilla();

        $box->append_template(__DIR__ . '/resources/template.txt');
        $box->append_template(__DIR__ . '/resources/whatever.php');
        $box->append_template(__DIR__ . '/resources/markdown.md');

        $expected = file_get_contents(__DIR__ . '/resources/template.txt')
            . file_get_contents(__DIR__ . '/resources/whatever.php')
            . file_get_contents(__DIR__ . '/resources/markdown.md');

        $this->assertEquals(trim($expected), trim($box->render()));
    }

    public function test_prepend_template()
    {
        $box = endobox\endobox::get()->vanilla();

        $box->prepend_template(__DIR__ . '/resources/markdown.md');
        $box->prepend_template(__DIR__ . '/resources/whatever.php');
        $box->prepend_template(__DIR__ . '/resources/template.txt');

        $expected = file_get_contents(__DIR__ . '/resources/template.txt')
            . file_get_contents(__DIR__ . '/resources/whatever.php')
            . file_get_contents(__DIR__ . '/resources/markdown.md');

        $this->assertEquals(trim($expected), trim($box->render()));
    }

}
