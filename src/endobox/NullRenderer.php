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
 * The most basic renderer that simply takes a renderable object and calls render.
 */
class NullRenderer implements Renderer
{

    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        return $input->render();
    }

}
