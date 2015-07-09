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
 * A TemplateBox is a box that allows you to append or prepend template files which will be parsed in some way
 * (depending on the concrete implementation of this abstract class).
 */
abstract class TemplateBox extends Box {

    protected $data = [];

    public abstract function append_template($t);

    public abstract function prepend_template($t);

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

    protected function load() {}

}
