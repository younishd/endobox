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
 * A MarkdownExtraBox is kinda like the MarkdownBox, only using the awesome extra syntax!
 */
class MarkdownExtraBox extends VanillaBox {

    private static $parser = null;

    protected function build($code)
    {
        return self::parse($code);
    }

    private static function parse($code)
    {
        if (self::$parser === null) {
            self::$parser = new \ParsedownExtra();
        }
        return self::$parser->parse($code);
    }

}
