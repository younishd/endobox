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
 * A Renderer is capable of rendering a Renderable object using an optional data array.
 */
interface Renderer
{

    /**
     * Render input using data and return the result.
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string;

}
