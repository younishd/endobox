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
 * This is a facade that helps you build box instances of different flavors.
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

    public function markdown()
    {
        return new core\MarkdownBox();
    }

    public function markdownextra()
    {
        return new core\MarkdownExtraBox();
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

function magic()
{
    return endobox::get()->magic();
}

function magic_e()
{
    return endobox::get()->endless()->magic();
}

function php()
{
    return endobox::get()->php();
}

function php_e()
{
    return endobox::get()->endless()->php();
}

function markdown()
{
    return endobox::get()->markdown();
}

function markdownextra()
{
    return endobox::get()->markdownextra();
}

function vanilla()
{
    return endobox::get()->vanilla();
}
