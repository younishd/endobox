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
 * This decorator adds a markdown functionality to a renderer.
 */
class MarkdownRendererDecorator extends RendererDecorator
{

    private static $instance = null;

    /**
     * Render by passing the inherited result through the markdown parser.
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        return self::instance()->text(parent::render($input, $data, $shared));
    }

    private static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new \Parsedown();
        }
        return self::$instance;
    }

}
