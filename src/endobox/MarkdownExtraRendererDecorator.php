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

/**
 *
 */
class MarkdownExtraRendererDecorator extends RendererDecorator
{

    /**
     * 
     */
    public function render(Renderable $input, array &$data = null) : string
    {
        return \ParsedownExtra::instance()->text(parent::render($input, $data));
    }

}