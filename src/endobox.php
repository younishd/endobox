<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

// add some function shortcuts here

class endobox {
    
    private $flags;
    
    public function __construct()
    {
        $this->flags = [
            'template' => false,
            'php' => false,
            'markdown' => false,
            'plain' => false,
            'endless' => false
        ];
    }
    
    public static function vanilla()
    {
        return new core\VanillaBox();
    }
    
    public static function with()
    {
        return new static();
    }
    
    public function get()
    {
        
    }
    
    public function php()
    {
        
    }
    
    public function markdown()
    {
        
    }
    
    public function plain()
    {
        
    }
    
    public function endless()
    {
        
    }
    
}
