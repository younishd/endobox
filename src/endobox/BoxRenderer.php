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

// TODO perhaps rename all implementations accordingly as well
// or revert back to just Renderer
//
interface BoxRenderer
{

    public function render(Box $box, array $shared = null) : string;

}
