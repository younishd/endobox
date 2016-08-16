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
class Box implements Renderable, \IteratorAggregate
{

    private $interior;

    private $renderer;

    private $data = [];

    private $next = null;

    private $prev = null;

    /**
     *
     */
    public function __construct(Renderable $interior, Renderer $renderer = null, array &$data = null)
    {
        $this->interior = $interior;
        $this->renderer = $renderer ?? new NullRenderer();
        if ($data !== null) {
            $this->data = &$data;
        }
    }

    /**
     *
     */
    public function __invoke(Box $b) : Box
    {
        return $this->append($b);
    }

    /**
     *
     */
    public function __toString() : string
    {
        return $this->render();
    }

    /**
     *
     */
    public function render() : string
    {
        $result = '';
        foreach ($this as $box) {
            $result .= $box->render();
        }
        return $result;
    }

    /**
     *
     */
    public function append(Box $b) : Box
    {

    }

    /**
     *
     */
    public function prepend(Box $b) : Box
    {

    }

    /**
     *
     */
    public function merge() : Box
    {
        $b = clone $this;

        if ($this->prev !== null) {
            $this->prev->next = $b;
        }
        if ($this->next !== null) {
            $this->next->prev = $b;
        }

        $this->prev = $this->next = null;
        $this->interior = $b;
        $this->renderer = new NullRenderer();

        return $this;
    }

    /**
     *
     */
    public function assign(array $data) : Box
    {

    }

    /**
     *
     */
    public function next() : Box
    {
        return $this->next;
    }

    /**
     *
     */
    public function prev() : Box
    {
        return $this->prev;
    }

    /**
     *
     */
    public function head() : Box
    {

    }

    /**
     *
     */
    public function tail() : Box
    {

    }

    /**
     *
     */
    public function getIterator()
    {
        return new BoxIterator($this);
    }

}
