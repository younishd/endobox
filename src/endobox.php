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

abstract class endobox {
    
    public static function vanilla()
    {
        return new core\VanillaBox();
    }
    
    public static function with()
    {
        return new builder\BoxBuilder();
    }
    
}
