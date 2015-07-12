<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox\core;

/**
 * A MarkdownBox allows you to append or prepend Markdown templates which will then get parsed to HTML code.
 */
class MarkdownBox extends VanillaBox {

    private static $parser = null;

    protected function build($code)
    {
        return self::parse($code);
    }

    private static function parse($code)
    {
        if (self::$parser === null) {
            self::$parser = \Parsedown::instance();
        }
        return self::$parser->parse($code);
    }

}
