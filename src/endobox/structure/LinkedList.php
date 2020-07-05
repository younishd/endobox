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

namespace endobox\structure;

use endobox\renderable\Box;

trait LinkedList
{

    private $next = null;

    private $prev = null;

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
        $visited = [];
        for ($b = $this; $b->prev !== null; $b = $b->markAsVisited($visited)->prev);
        return $b;
    }

    public function tail() : Box
    {
        $visited = [];
        for ($b = $this; $b->next !== null; $b = $b->markAsVisited($visited)->next);
        return $b;
    }

    private function appendBox(Box $box)
    {
        if ($this->next === null && $box->prev === null) {
            $this->next = $box;
            $box->prev = $this;
        } else {
            $this->tail()->appendBox($box->head());
        }
    }

    private function markAsVisited(&$visited)
    {
        // keep track of visited boxes for cycle detection
        $key = \spl_object_hash($this);
        if (isset($visited[$key])) {
            throw new \RuntimeException("Cycle detected in box graph.");
        }
        $visited[$key] = true;

        return $this;
    }

}
