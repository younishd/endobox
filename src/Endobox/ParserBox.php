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

class ParserBox extends Box {
    
    private $parser = null;
    
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function load()
    {
        
    }
    
    public function build()
    {
        $this->code = $this->parser($this->code);
    }
    
}
