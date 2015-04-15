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
 * This parser evaluates the given code as PHP until there is no opening PHP tag left.
 * 
 * Note that this class makes use of eval and output buffering.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class EndlessPHPParser implements Parser {
    
    private $data;
    
    public function __construct(&$data)
    {
        if ($data === null) {
            throw new \InvalidArgumentException('Data array is null.');
        }
        $this->data =& $data;
    }
    
    public function parse($code)
    {
        \ob_start();
        while (\strpos($code, '<?php') !== false) {
            eval('?>' .  $code);
            $code = \ob_get_contents();
        }
        \ob_end_clean();
        return $code;
    }
    
}
