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
 * Facade with a good default combination of appropriate dependencies.
 */
abstract class Endobox
{

    /**
     * Create and return a BoxFactory that looks into the given path for template files.
     */
    public static function create(string $path)
    {
        return new BoxFactory($path, new \Parsedown());
    }

}
