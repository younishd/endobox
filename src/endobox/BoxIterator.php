<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class BoxIterator implements \Iterator
{

    private $aggregate;

    private $current = null;

    private $position = 0;

    public function __construct(Box $b)
    {
        $this->aggregate = $b;
    }

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->current = $this->current->next();
        ++$this->position;
    }

    public function rewind()
    {
        $this->current = $this->aggregate->head();
        $this->position = 0;
    }

    public function valid()
    {
        return $this->current !== null;
    }

}
