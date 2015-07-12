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
 * A TemplateBox is a box that allows you to append/prepend template files and assign data.
 * The data array can (optionally) be passed by reference to the constructor.
 *
 * This could for instance be useful to pass some shared data array to several template boxes.
 * (This concept is being used in the MagicBox class.)
 */
abstract class TemplateBox extends VanillaBox {

    protected $data = [];

    public function __construct(array &$data = null)
    {
        if ($data !== null) {
            $this->data =& $data;
        }
    }

    public function assign($key, $value = null)
    {
        if (\is_array($key)) {
            $this->data = \array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

}
