<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class TemplateTest extends PHPUnit_Framework_TestCase {

    public function test_empty_file()
    {
        $t = new Endobox\Template(__DIR__ . '/resources/empty');
        $this->assertSame('', $t->render());
    }
    
    public function test_nonexistent_file()
    {
        $this->setExpectedException('InvalidArgumentException');
        $t = new Endobox\Template(__DIR__ . '/resources/bf78gb4xf3');
        $t->render();
    }
    
    public function test_template_file()
    {
        $t = new Endobox\Template(__DIR__ . '/resources/template.txt');
        $this->assertSame(trim("Hello!\nI'm a template."), trim($t->render()));
    }

}
