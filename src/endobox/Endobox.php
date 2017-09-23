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

use \Pimple\Container;

/**
 * Bootstrap code.
 */
abstract class Endobox
{

    /**
     * Create and return a Factory that looks into the given path for template files.
     */
    public static function create(string $path)
    {
        return new Factory($path, new Container());
    }

}
