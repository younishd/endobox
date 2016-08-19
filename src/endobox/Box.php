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

    /**
     * Union-find rank.
     */
    private $rank = 0;

    /**
     * Union-find parent.
     */
    private $parent;

    /**
     * Union-find next child in circular linked list.
     * This is used to be able to traverse the set.
     */
    private $child;

    /**
     * Next Box in linked list.
     */
    private $next = null;

    /**
     * Previous Box in linked list.
     */
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

        $this->parent = $this;
        $this->child = $this;
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
        $shared = [];
        foreach ($this as $box) {

            // if box has no shared data
            if ($box->child === $box) {
                // render, concat, and continue
                $result .= $box->renderer->render($box->interior, $box->data);
                continue;
            }

            // cache union sets of shared data arrays using root as key
            $root = $box->find();
            if (!isset($shared[$root])) {
                $shared[$root] = [];
                $shared[$root][] = &$root->data;
                for ($i = $root->child; $i !== $root; $i = $i->child) {
                    $shared[$root][] = &$i->data;
                }
            }

            // render with shared data and concat results
            $result .= $box->renderer->render($box->interior, $box->data, $shared[$root]);

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
     * Union by rank.
     */
    public function entangle(Box $b) : Box
    {
        $root1 = $this->find();
        $root2 = $b->find();

        // if already in same set then nothing to do
        if ($root1 === $root2) {
            return $this;
        }

        // union by rank
        if ($root1->rank > $root2->rank) {
            $root2->parent = $root1;
        } elseif ($root2->rank > $root1->rank) {
            $root1->parent = $root2;
        } else {
            $root2->parent = $root1;
            ++$root1->rank;
        }

        // merge circular linked lists
        $tmp = $this->child;
        $this->child = $b->child;
        $b->child = $tmp;

        return $this;
    }

    /**
     * Find with path compression.
     */
    private function find() : Box
    {
        // path compression
        if ($this->parent !== $this) {
            $this->parent = $this->parent.find();
        }
        return $this->parent;
    }

    /**
     * Merge the linked list of Boxes into one Box and return this instance.
     * TODO what about the data?
     */
    /*public function merge() : Box
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
    }*/

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
