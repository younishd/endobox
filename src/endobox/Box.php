<?php

declare(strict_types = 1);

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class Box implements Renderable, \IteratorAggregate
{

    private $interior;

    private $renderer;

    private $data = [];

    private $rank = 0;

    private $parent;

    private $child;

    private $next = null;

    private $prev = null;

    private $late_assign_flag = false;

    private static $render_root_box = null;

    public function __construct(
        Renderable $interior,
        BoxRenderer $renderer,
        array &$data = null
    ) {
        $this->interior = $interior;
        $this->renderer = $renderer;

        if ($data !== null) {
            $this->data = &$data;
        }

        $this->parent = $this;
        $this->child = $this;
    }

    public function __invoke($arg = null) : Box
    {
        if ($arg === null) {
            return $this->linkAll();
        }

        if (\is_array($arg)) {
            return $this->assign($arg);
        }

        return $this->append($arg);
    }

    public function __toString() : string
    {
        return $this->render();
    }

    public function __set(string $key, $value)
    {
        $this->assign([ $key => $value ]);
    }

    public function __get(string $key)
    {
        if (!isset($this->data[$key])) {
            throw new \UnderflowException(\sprintf('Key "%s" does not exist.', $key));
        }

        return $this->data[$key];
    }

    public function __isset(string $key) : bool
    {
        return isset($this->data[$key]);
    }

    public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    public function __clone()
    {
        $this->interior = clone $this->interior;
        $this->renderer = clone $this->renderer;

        // detach the cloned box
        $this->rank = 0;
        $this->parent = $this;
        $this->child = $this;
        $this->next = null;
        $this->prev = null;
        $this->late_assign_flag = false;
    }

    public function render(array $data = null) : string
    {
        try {

            self::$render_root_box = self::$render_root_box ?? $this;

            if ($data !== null) {
                $this->assign($data);
            }

            $result = $this->renderAll();

            // 2 pass render
            if (
                self::$render_root_box === $this
                && $this->getLateAssignFlag()
            ) {
                $result = $this->renderAll();
            }

            return $result;

        } catch (\Throwable $e) {

            throw new $e;

        } finally {

            if (self::$render_root_box === $this) {
                self::$render_root_box = null;
            }

        }
    }

    public function create(string $template) : Box
    {
        return ($this->box)($template);
    }

    public function getContext() : string
    {
        return $this->interior->getContext();
    }

    public function append($arg) : Box
    {
        if ($arg instanceof Box) {
            if ($this->next === null && $arg->prev === null) {
                $this->next = $arg;
                $arg->prev = $this;
            } else {
                $this->tail()->append($arg->head());
            }
            return $this;
        }

        return $this->append($this->create((string)$arg));
    }

    public function prepend($arg) : Box
    {
        if ($arg instanceof Box) {
            $arg->append($this);
            return $this;
        }

        return $this->prepend($this->create((string)$arg));
    }

    public function link(Box $b = null) : Box
    {
        if ($b === null) {
            return $this->linkAll();
        }

        $root1 = $this->find();
        $root2 = $b->find();

        if ($root1 === $root2) {
            return $this;
        }

        $late = false;
        if ($root1->getLateAssignFlag()) {
            $root1->resetLateAssignFlag();
            $late = true;
        }
        if ($root2->getLateAssignFlag()) {
            $root2->resetLateAssignFlag();
            $late = true;
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

        if ($late) {
            $this->setLateAssignFlag();
        }

        return $this;
    }

    public function entangle(Box $b) : Box
    {
        // DEPRECATED - use link() instead
        return $this->link($b);
    }

    public function assign(array $data) : Box
    {
        if (self::$render_root_box !== null) {
            $this->setLateAssignFlag();
        }

        foreach ($data as $k => &$v) {

            // allow passing closures as data by wrapping it in an anonymous object
            if ($v instanceof \Closure) {
                $data[$k] = new class($v) {
                    private $closure;
                    public function __construct($c) { $this->closure = $c; }
                    public function __toString() { return \call_user_func($this->closure); }
                    public function __invoke(...$args) { return \call_user_func_array($this->closure, $args); }
                };

            // auto link boxes
            } elseif ($v instanceof Box) {
                $v->link($this);
            }

        }

        $this->data = \array_merge($this->data, $data);

        return $this;
    }

    public function next()
    {
        return $this->next;
    }

    public function prev()
    {
        return $this->prev;
    }

    public function head() : Box
    {
        // keep track of visited boxes for cycle detection
        $touched = [];
        for ($b = $this; $b->prev !== null; $b = $b->visit($touched)->prev);
        return $b;
    }

    public function tail() : Box
    {
        // keep track of visited boxes for cycle detection
        $touched = [];
        for ($b = $this; $b->next !== null; $b = $b->visit($touched)->next);
        return $b;
    }

    public function getIterator()
    {
        return new BoxIterator($this);
    }

    public function getInterior() : Renderable
    {
        return $this->interior;
    }

    public function &getData() : array
    {
        return $this->data;
    }

    private function renderAll() : string
    {
        $result = '';
        $shared = [];

        foreach ($this as $box) {
            // if box has no shared data
            if ($box->child === $box) {
                $result .= $box->renderSelf();
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

            $result .= $box->renderSelf($shared[$key]);
        }

        return $result;
    }

    private function renderSelf(array $shared = null) : string
    {
        if ($shared === null) {
            return $this->renderer->render($this);
        }
        return $this->renderer->render($this, $shared);
    }

    private function getLateAssignFlag() : bool
    {
        return $this->find()->late_assign_flag;
    }

    private function setLateAssignFlag()
    {
        $this->find()->late_assign_flag = true;
    }

    private function resetLateAssignFlag()
    {
        $this->find()->late_assign_flag = false;
    }

    private function find() : Box
    {
        // find with path compression
        if ($this->parent !== $this) {
            $this->parent = $this->parent->find();
        }
        return $this->parent;
    }

    private function visit(&$touched)
    {
        // detect cycle
        $key = \spl_object_hash($this);
        if (isset($touched[$key])) {
            throw new \RuntimeException("Cycle detected in box graph.");
        }
        $touched[$key] = true;
        // just for convenience
        return $this;
    }

    private function linkAll() : Box
    {
        for ($b = $this->child; $b !== $this; $b = $b->child) {
            $this->link($b);
        }

        return $this;
    }

}
