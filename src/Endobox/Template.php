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

class Template implements Renderable {
    
    private $filename = '';

    public function __construct($filename)
    {
        $this->filename = (string)$filename;
    }
    
    public function render()
    {
        if (!file_exists($this->filename)) {
            throw new Exception(sprintf('Template file %s does not exist.', $this->filename));
        }
        return file_get_contents($this->filename);
    }
    
}
