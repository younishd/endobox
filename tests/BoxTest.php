<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class BoxTest extends PHPUnit_Framework_TestCase {
    
    public function test_empty()
    {
        $box = new Endobox\Box();
        $this->assertNull($box->next());
        $this->assertNull($box->prev());
    }
    
    public function test_append()
    {
        $first = new Endobox\Box();
        $second = new Endobox\Box();
        $third = new Endobox\Box();
        
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
        $first = new Endobox\Box();
        $second = new Endobox\Box();
        $third = new Endobox\Box();
        
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
        $box = new Endobox\Box();
        
        $first = $this->getMockBuilder('Endobox\Box')
            ->setMethods(['render_inner'])
            ->getMock();
        
        $second = $this->getMockBuilder('Endobox\Box')
            ->setMethods(['render_inner'])
            ->getMock();
        
        $third = $this->getMockBuilder('Endobox\Box')
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
        $box = new Endobox\Box();
        
        $this->assertSame('', $box->render());
    }
    
    public function test_render_inner()
    {
        $box = new Endobox\Box();
        
        $first = $this->getMockBuilder('Endobox\Box')
            ->setMethods(['render'])
            ->getMock();
        
        $second = $this->getMockBuilder('Endobox\Box')
            ->setMethods(['render'])
            ->getMock();
        
        $third = $this->getMockBuilder('Endobox\Box')
            ->setMethods(['render'])
            ->getMock();
        
        $first->method('render')->willReturn('First');
        $second->method('render')->willReturn('Second');
        $third->method('render')->willReturn('Third');
        
        $reflector = new ReflectionClass('Endobox\Box');
        $method_append_inner = $reflector->getMethod('append_inner');
        $method_append_inner->setAccessible(true);
        $method_append_inner->invokeArgs($box, [$first]);
        $method_append_inner->invokeArgs($box, [$second]);
        $method_append_inner->invokeArgs($box, [$third]);
        
        $expected = 'FirstSecondThird';
        
        $this->assertEquals($expected, $box->render_inner());
        
        $method_prepend_inner = $reflector->getMethod('prepend_inner');
        $method_prepend_inner->setAccessible(true);
        $method_prepend_inner->invokeArgs($box, [$first]);
        $method_prepend_inner->invokeArgs($box, [$second]);
        $method_prepend_inner->invokeArgs($box, [$third]);
        
        $expected = 'ThirdSecondFirstFirstSecondThird';
        
        $this->assertEquals($expected, $box->render_inner());
    }
    
    public function test_render_inner_empty()
    {
        $box = new Endobox\Box();
        
        $this->assertSame('', $box->render_inner());
    }
    
}
