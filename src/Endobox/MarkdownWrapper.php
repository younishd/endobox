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

class MarkdownWrapper implements Parser {
    
    private $instance = null;
    
    public function __construct()
    {
        $this->instance = \Parsedown::instance();
    }
    
    public function parse($code)
    {
        $this->instance->parse($code);
    }
    
}
