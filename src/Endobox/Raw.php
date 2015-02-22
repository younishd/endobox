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

class Raw implements Renderable {

    private $text;

    public function __construct($text)
    {
        $this->text = (string)$text;
    }
    
    public function render()
    {
        return $this->text;
    }
    
}
