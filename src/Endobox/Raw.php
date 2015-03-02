<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Endobox;

/**
 * A raw renderable is just plain text.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class Raw implements Renderable {
    
    private $text;
    
    /**
     * @param string $text Some text.
     */
    public function __construct($text)
    {
        $this->text = (string)$text;
    }
    
    public function render()
    {
        return $this->text;
    }
    
}
