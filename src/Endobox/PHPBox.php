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
 * PHPBox evaluates the rendered code as PHP until there is no opening PHP tag left.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class PHPBox extends Box {
    
    public function load() {}
    
    public function build()
    {
        ob_start();
        while (strpos($this->code, '<?php') !== false) {
            eval('?>' .  $this->code);
            $this->code = ob_get_contents();
        }
        ob_end_clean();
    }
    
}
