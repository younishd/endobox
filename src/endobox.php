<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * This class helps you build box instances of different flavors.
 */
class endobox {

    private $flags;

    public function __construct()
    {
        $this->flags = [
            'endless' => false
        ];
    }

    public static function get()
    {
        return new static();
    }

    public function vanilla()
    {
        return new core\VanillaBox();
    }

    public function plain()
    {
        return new core\PlainBox();
    }

    public function markdown()
    {
        return new core\MarkdownBox();
    }

    public function php()
    {
        $x = new core\PHPBox();
        $x->set_endless($this->flags['endless']);
        return $x;
    }

    public function magic()
    {
        $x = new core\MagicBox();
        $x->set_endless($this->flags['endless']);
        return $x;
    }

    public function endless()
    {
        $this->flags['endless'] = true;
        return $this;
    }

}

if (!\function_exists('endobox\\magic')) {
    function magic()
    {
        return endobox::get()->magic();
    }
}

if (!\function_exists('endobox\\magic_e')) {
    function magic_e()
    {
        return endobox::get()->endless()->magic();
    }
}

if (!\function_exists('endobox\\php')) {
    function php()
    {
        return endobox::get()->php();
    }
}

if (!\function_exists('endobox\\php_e')) {
    function php_e()
    {
        return endobox::get()->endless()->php();
    }
}

if (!\function_exists('endobox\\markdown')) {
    function markdown()
    {
        return endobox::get()->markdown();
    }
}

if (!\function_exists('endobox\\plain')) {
    function plain()
    {
        return endobox::get()->plain();
    }
}

if (!\function_exists('endobox\\vanilla')) {
    function vanilla()
    {
        return endobox::get()->vanilla();
    }
}
