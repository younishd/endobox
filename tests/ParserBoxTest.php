<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ParserBoxTest extends PHPUnit_Framework_TestCase {

    public function test_parse()
    {
        $expected = 'Hello';
        $parser = $this->getMockBuilder('Endobox\Parser')->getMock();
        $parser->method('parse')->willReturn($expected);
        
        $box = new Endobox\ParserBox($parser);
        $this->assertEquals($expected, $box->render());
    }

}
