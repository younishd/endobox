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
    
    protected $data = [];

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
    
    /**
     * Assign some data to this box.
     * 
     * The data is stored in an array and can be accessed via the $this->data property
     * from within the evaluated PHP code.
     * 
     * It is possible to assign a single key-value couple or a whole array of data at once
     * by omitting the second argument.
     * 
     * @param mixed|array $key The data key or an array of data.
     * @param mixed|null $value The data value or null.
     * @return This very instance.
     */
    public function assign($key, $value = null)
    {
        if (is_array($key) === true) {
            $this->data = \array_merge($this->data, $key);
        }
        else {
            $this->data[$key] = $value;
        }
        
        return $this;
    }
    
}
