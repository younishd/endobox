<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace endobox\renderable;

use endobox\iterator\BoxIterator;
use endobox\renderer\Renderer;
use endobox\structure\LinkedList;
use endobox\structure\UnionFind;

class Box implements Renderable, \IteratorAggregate
{

    use LinkedList;

    use UnionFind;

    private $interior;

    private $renderer;

    private $data = [];

    private static $render_root_box = null;

    public function __construct(
        Renderable $interior,
        Renderer $renderer,
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
            return $this->link();
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

            // mark as render root unless already set
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
            $this->appendBox($arg);
            return $this;
        }

        return $this->append($this->create((string)$arg));
    }

    public function prepend($arg) : Box
    {
        if ($arg instanceof Box) {
            $arg->appendBox($this);
            return $this;
        }

        return $this->prepend($this->create((string)$arg));
    }

    public function link(Box $box = null) : Box
    {
        if ($box === null) {
            return $this->linkAll();
        }

        return $this->union($box);
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

    public function getIterator() : \Iterator
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

    private function linkAll() : Box
    {
        foreach ($this as $box) {
            $this->link($box);
        }

        return $this;
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

}
