<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2016 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class Engine
{

    private $path;

    public function __construct($path)
    {
        if (!is_dir($path)) {
            throw new \RuntimeException(sprintf('The path "%s" does not exist or is not a directory.', $path));
        }
        $this->path = $path;
    }

    public function __invoke($template) : Box
    {
        return $this->spawn($template);
    }

    public function spawn($template) : Box
    {

    }

}
