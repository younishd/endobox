<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class RawTest extends PHPUnit_Framework_TestCase {

    public function test_empty_string()
    {
        $test = '';
        $raw = new Endobox\Raw($test);
        $this->assertSame($test, $raw->render());
    }
    
    public function test_sample_string()
    {
        $test = 'Writing Tests for PHPUnit';
        $raw = new Endobox\Raw($test);
        $this->assertSame($test, $raw->render());
    }

}
