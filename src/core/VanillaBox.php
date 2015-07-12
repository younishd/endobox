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
 * A VanillaBox is basically just the plain box structure without anything special.
 * It allows you to append or prepend plain text files as templates.
 * Their content won't be touched. What you put in comes out.
 *
 * However, this class can be used as a base class to implement
 * more complex template boxes (e.g., Markdown, PHP)...
 */
class VanillaBox extends Box {

    public function append_template($t)
    {
        return $this->append_inner(new File($t));
    }

    public function prepend_template($t)
    {
        return $this->prepend_inner(new File($t));
    }

    protected function load() {}

    protected function build($code)
    {
        return $code;
    }

}
