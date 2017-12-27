<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2017 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * The fundamental data structure of endobox.
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

    public function __construct(Renderable $interior, Renderer $renderer, array &$data = null)
    {
        $this->interior = $interior;
        $this->renderer = $renderer;

        if ($data !== null) {
            $this->data = &$data;
        }

        $this->parent = $this;
        $this->child = $this;
    }

    /**
     * Invoking a box object like a function is short for append.
     */
    public function __invoke(Box $b) : Box
    {
        return $this->append($b);
    }

    /**
     * Treating a box object like a string calls render.
     */
    public function __toString() : string
    {
        return $this->render();
    }

    /**
     * Set data using property syntax.
     */
    public function __set(string $key, $value)
    {
        $this->assign([ $key => $value ]);
    }

    /**
     * Get data using property syntax.
     */
    public function __get(string $key)
    {
        if (!isset($this->data[$key])) {
            throw new \UnderflowException(\sprintf('Key "%s" does not exist.', $key));
        }

        return $this->data[$key];
    }

    /**
     * Check if a key is set using property syntax.
     */
    public function __isset(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Unset a key using property syntax.
     */
    public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    /**
     * Clone a box.
     */
    public function __clone()
    {
        $this->interior = clone $this->interior;
        $this->renderer = clone $this->renderer;

        // detach the cloned box from any appended or entangled boxes
        $this->rank = 0;
        $this->parent = $this;
        $this->child = $this;
        $this->next = null;
        $this->prev = null;
    }

    /**
     * Render the box and everything attached to it then return the result.
     */
    public function render() : string
    {
        // assign data if any
        if (\func_num_args() > 0) {
            // php 7.2.0 is crazy
            $args = \func_get_args();
            $this->assign($args[0]);
        }

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
            $key = \spl_object_hash($root);
            if (!isset($shared[$key])) {
                $shared[$key] = [];
                $shared[$key][] = &$root->data;
                for ($i = $root->child; $i !== $root; $i = $i->child) {
                    $shared[$key][] = &$i->data;
                }
            }

            // render with shared data and concat results
            $result .= $box->renderer->render($box->interior, $box->data, $shared[$key]);
        }

        return $result;
    }

    public function get_context() : string
    {
        return $this->interior->get_context();
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
        $b->append($this);
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
     * Assign some data to this Box.
     */
    public function assign(array $data) : Box
    {
        // Allow passing closures as data.
        // The trick is to wrap the closure in an anonymous class instance that takes the closure and calls it
        // when it is invoked as a string or as a function.
        // We only support real closures (i.e., instance of Closure), not just any callable,
        // because there is no way to know if "time" is supposed to be data or a function name.
        foreach ($data as $k => &$v) {
            if ($v instanceof \Closure) {
                $data[$k] = new class($v) {
                    private $closure;
                    public function __construct($c) { $this->closure = $c; }
                    public function __toString() { return \call_user_func($this->closure); }
                    public function __invoke(...$args) { return \call_user_func_array($this->closure, $args); }
                };
            }
        }

        $this->data = \array_merge($this->data, $data);

        return $this;
    }

    /**
     * Return next Box or null if this is the list's tail.
     */
    public function next()
    {
        return $this->next;
    }

    /**
     * Return previous Box or null if this is the list's head.
     */
    public function prev()
    {
        return $this->prev;
    }

    /**
     * Return the head of the list.
     */
    public function head() : Box
    {
        // keep track of visited boxes for cycle detection
        $touched = [];
        for ($b = $this; $b->prev !== null; $b = $b->visit($touched)->prev);
        return $b;
    }

    /**
     * Return the tail of the list.
     */
    public function tail() : Box
    {
        // keep track of visited boxes for cycle detection
        $touched = [];
        for ($b = $this; $b->next !== null; $b = $b->visit($touched)->next);
        return $b;
    }

    /**
     * Get a BoxIterator instance.
     */
    public function getIterator()
    {
        return new BoxIterator($this);
    }

    /**
     * Find with path compression.
     */
    private function find() : Box
    {
        // path compression
        if ($this->parent !== $this) {
            $this->parent = $this->parent->find();
        }
        return $this->parent;
    }

    /**
     * Mark this box as visited or throw a RuntimeException if we have detected a cycle in the graph.
     */
    private function visit(&$touched)
    {
        $key = \spl_object_hash($this);
        if (isset($touched[$key])) {
            throw new \RuntimeException("Cycle (endless loop) detected in box graph.");
        }
        $touched[$key] = true;
        // just for convenience
        return $this;
    }

}
