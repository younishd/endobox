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
 * ParserBox passes the rendered code through a given parser before returning it.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class ParserBox extends Box {
    
    private $parser = null;
    
    /**
     * @param \Endobox\Parser $parser The parser instance.
     */
    public function __construct(Parser $parser)
    {
        if ($parser === null) {
            throw new \InvalidArgumentException('Parser instance is null.');
        }
        $this->parser = $parser;
    }
    
    /**
     * Run the code through the parser and return the result.
     * 
     * @param string $code The initial code.
     * @return string The parsed code.
     */
    protected function build($code)
    {
        return $this->parser->parse($code);
    }
    
}
