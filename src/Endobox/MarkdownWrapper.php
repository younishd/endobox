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

/**
 * This is just a wrapper class for a markdown parser class.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class MarkdownWrapper implements Parser {
    
    private $instance = null;
    
    public function __construct()
    {
        $this->instance = \Parsedown::instance();
    }
    
    public function parse($code)
    {
        return $this->instance->parse($code);
    }
    
}
