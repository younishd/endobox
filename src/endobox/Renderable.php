<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2019 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * A Renderable can be rendered to a string.
 */
interface Renderable
{

    /**
     * This is called when the Renderable object is treated like a string.
     * Should probably just delegate the task to the render() method.
     */
    public function __toString() : string;

    /**
     * Render and return result.
     */
    public function render() : string;

    /**
     * Get some meta info about what this renderable object is and where it came from.
     */
    public function get_context() : string;

}
