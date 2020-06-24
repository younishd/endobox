<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class Atom implements Renderable
{

    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString() : string
    {
        return $this->text;
    }

    public function render() : string
    {
        return $this->text;
    }

    public function getContext() : string
    {
        return "atom";
    }

}
