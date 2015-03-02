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
 * MarkdownBox parses the rendered code as markdown before returning it using some markdown parser.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class MarkdownBox extends ParserBox {
    
    public function __construct()
    {
        parent::__construct(new MarkdownParser());
    }
    
}
