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
 * Decorator for Renderer class.
 */
abstract class RendererDecorator implements Renderer
{

    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Delegate render functionality to renderer.
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        return $this->renderer->render($input, $data, $shared);
    }

}
