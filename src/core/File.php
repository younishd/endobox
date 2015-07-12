<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox\core;

class File implements Renderable {

    private $filename;

    public function __construct($filename)
    {
        $this->filename = (string)$filename;
    }

    public function render()
    {
        if (!\file_exists($this->filename)) {
            throw new \InvalidArgumentException(\sprintf('File %s does not exist.', $this->filename));
        }
        return \file_get_contents($this->filename);
    }

}
