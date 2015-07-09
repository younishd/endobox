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
 * A Box is a data structure that allows building larger things from smaller things.
 * It's a kind of fancy linked list that helps you build up your template-based web page.
 */
abstract class Box {

    protected $interior = [];

    protected $next = null;

    protected $prev = null;

    protected abstract function load();

    protected abstract function build($code);

    public function render()
    {
        $code = '';
        for ($b = $this->head(); $b !== null; $b = $b->next) {
            $code .= $b->render_inner();
        }
        return $code;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function append(Box $b)
    {
        if ($this->next === null) {
            if ($b->prev === null) {
                $this->next = $b;
                $b->prev = $this;
                return $this;
            }
            return $this->append($b->prev);
        }
        return $this->next->append($b);
    }

    public function prepend(Box $b)
    {
        if ($this->prev === null) {
            if ($b->next === null) {
                $this->prev = $b;
                $b->next = $this;
                return $this;
            }
            return $this->prepend($b->next);
        }
        return $this->prev->prepend($b);
    }

    public function next()
    {
        return $this->next;
    }

    public function prev()
    {
        return $this->prev;
    }

    public function head()
    {
        for ($b = $this; $b->prev !== null; $b = $b->prev);
        return $b;
    }

    public function tail()
    {
        for ($b = $this; $b->next !== null; $b = $b->next);
        return $b;
    }

    protected function render_inner()
    {
        $code = '';
        $this->load();
        foreach ($this->interior as $r) {
            $code .= $r->render();
        }
        return $this->build($code);
    }

    protected function append_inner(Box $box)
    {
        $this->interior[] = $r;
        return $this;
    }

    protected function prepend_inner(Box $box)
    {
        \array_unshift($this->interior, $r);
        return $this;
    }

}
