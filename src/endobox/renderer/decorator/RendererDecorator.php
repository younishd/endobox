<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace endobox\renderer\decorator;

use endobox\renderable\Box;
use endobox\renderer\Renderer;

abstract class RendererDecorator implements Renderer
{

    private $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function render(Box $box, array $shared = null) : string
    {
        return $this->renderer->render($box, $shared);
    }

}
