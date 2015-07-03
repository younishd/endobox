<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox\core;

abstract class Box {
    
    protected $interior = [];
    
    protected $next = null;
    protected $prev = null;

    public function render()
    {
        
    }
    
    public function __toString()
    {
        
    }
    
    public function append(Box $box)
    {
        
    }
    
    public function prepend(Box $box)
    {
        
    }
    
    public function next()
    {
        
    }
    
    public function prev()
    {
        
    }
    
    public function head()
    {
        
    }
    
    public function tail()
    {
        
    }
    
    protected abstract function load();
    
    protected abstract function build($code);
    
    protected function render_inner()
    {
        
    }
    
    protected function append_inner(Box $box)
    {
        
    }
    
    protected function prepend_inner(Box $box)
    {
        
    }
    
}
