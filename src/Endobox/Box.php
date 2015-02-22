<?php

/*
 * This file is part of Endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Endobox;

abstract class Box implements Renderable {
    
    private $interior = [];
    
    protected $code = '';

    protected abstract function load();
    
    protected abstract function build();
    
    public function __toString()
    {
        return $this->render();
    }
    
    protected function append(Renderable $r)
    {
        $this->interior[] = $r;
    }
    
    protected function prepend(Renderable $r)
    {
        array_unshift($this->interior, $r);
    }
    
    public function render()
    {
        $this->load();
        foreach ($this->interior as $r) {
            $this->$code .= $r->render();
        }
        $this->build();
        return $this->code;
    }
    
}
