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

class MarkdownRendererDecorator extends RendererDecorator
{

    private $parsedown;

    public function __construct(BoxRenderer $renderer, \Parsedown $parsedown)
    {
        parent::__construct($renderer);
        $this->parsedown = $parsedown;
    }

    public function render(Box $box, array $shared = null) : string
    {
        return $this->parsedown->text(parent::render($box, $shared));
    }

}
