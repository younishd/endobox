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

    private static $instance = null;

    /**
     *
     */
    public function render(Renderable $input, array &$data = null, array $shared = null) : string
    {
        return self::instance()->text(parent::render($input, $data, $shared));
    }

    private static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new \ParsedownExtra();
        }
        return self::$instance;
    }

}
