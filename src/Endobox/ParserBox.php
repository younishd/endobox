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
 * ParserBox passes the rendered code through a given parser before returning it.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class ParserBox extends Box {
    
    private $parser = null;
    
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function load() {}
    
    public function build()
    {
        $this->code = $this->parser->parse($this->code);
    }
    
}
