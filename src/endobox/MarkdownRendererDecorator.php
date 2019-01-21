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
 * This decorator adds markdown functionality to a renderer using Parsedown.
 */
class MarkdownRendererDecorator extends RendererDecorator
{

    private $parsedown;

    public function __construct(Renderer $renderer, \Parsedown $parsedown)
    {
        parent::__construct($renderer);
        $this->parsedown = $parsedown;
    }

    /**
     * Render by passing the inherited result through Parsedown.
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        return $this->parsedown->text(parent::render($input, $data, $shared));
    }

}
