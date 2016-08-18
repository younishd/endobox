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
            $result .= $box->renderer->render($box->interior, $box->data);
        }
        return $result;
    }

    /**
     * Append a Box to the end of the linked list and return this instance.
     */
    public function append(Box $b) : Box
    {
        if ($this->next === null && $b->prev === null) {
            $this->next = $b;
            $b->prev = $this;
        } else {
            $this->tail()->append($b->head());
        }
        return $this;
    }

    /**
     * Prepend a Box to the beginning of the linked list and return this instance.
     */
    public function prepend(Box $b) : Box
    {
        if ($this->prev === null && $b->next === null) {
            $this->prev = $b;
            $b->next = $this;
        } else {
            $this->head()->prepend($b->tail());
        }
        return $this;
    }

    /**
     * Merge the linked list of Boxes into one Box and return this instance.
     * TODO what about the data?
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
     * Assign some data to this Box.
     */
    public function assign(array $data) : Box
    {

    }

    /**
     * Return next Box or null if this is the list's tail.
     */
    public function next() : Box
    {
        return $this->next;
    }

    /**
     * Return previous Box or null if this is the list's head.
     */
    public function prev() : Box
    {
        return $this->prev;
    }

    /**
     * Return the head of the list.
     */
    public function head() : Box
    {
        for ($b = $this; $b->prev !== null; $b = $b->prev);
        return $b;
    }

    /**
     * Return the tail of the list.
     */
    public function tail() : Box
    {
        for ($b = $this; $b->next !== null; $b = $b->next);
        return $b;
    }

    /**
     * Get a BoxIterator instance.
     */
    public function getIterator()
    {
        return new BoxIterator($this);
    }

}
