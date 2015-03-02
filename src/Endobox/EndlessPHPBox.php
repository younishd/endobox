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
 * EndlessPHPBox evaluates the rendered code using an EndlessPHPParser.
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class EndlessPHPBox extends ParserBox {
    
    protected $data = [];
    
    public function __construct()
    {
        parent::__construct(new EndlessPHPParser());
    }
    
    /**
     * Assign some data to this box.
     * 
     * The data is stored in an assoc array and can be accessed via the $this->data property
     * from within the evaluated PHP code.
     * 
     * It is possible to assign a single key-value couple or a whole array of data at once
     * by passing an array as key instead and omitting the second value argument.
     * 
     * @param mixed|array $key The data key or an array of data.
     * @param mixed|null $value The data value or null.
     * @return \Endobox\PHPBox This very instance.
     */
    public function assign($key, $value = null)
    {
        if (\is_array($key)) {
            $this->data = \array_merge($this->data, $key);
        }
        else {
            $this->data[$key] = $value;
        }
        return $this;
    }
    
}
