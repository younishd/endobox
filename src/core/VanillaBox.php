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

/**
 * A VanillaBox is basically just the plain box structure without anything special.
 * What you put in comes out.
 *
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class VanillaBox extends Box {

    protected function load() {}

    protected function build($code)
    {
        return $code;
    }

}
