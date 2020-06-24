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

abstract class Endobox
{

    public static function create(string $path)
    {
        return new BoxFactory($path, new \Parsedown());
    }

}
