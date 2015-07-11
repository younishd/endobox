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
 * A PHPBox allows you to append or prepend PHP templates which will then get evaluated.
 *
 * Note that this box allows data assignment via the assign() method.
 * The assigned data is accessible inside a template through the data[] attribute.
 */
class PHPBox extends TemplateBox {

    private $endless = false;

    public function append_template($t)
    {

    }

    public function prepend_template($t)
    {

    }

    public function set_endless($endless = true)
    {

    }

    protected function build($code)
    {

    }

}
