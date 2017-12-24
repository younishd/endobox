<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2017 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * Template wraps a plain text file as a renderable object.
 */
class Template implements Renderable
{
    
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function __toString() : string
    {
        return $this->render();
    }

    public function render() : string
    {
        return \file_get_contents($this->filename);
    }

    public function get_context() : string
    {
        return $this->filename;
    }

}
