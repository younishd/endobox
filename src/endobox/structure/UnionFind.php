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

trait UnionFind
{

    private $parent;

    private $child;

    private $rank = 0;

    private $late_assign_flag = false;

    private function union(Box $box)
    {
        $root1 = $this->find();
        $root2 = $box->find();

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
        $this->child = $box->child;
        $box->child = $tmp;

        // move late flag to new root if any
        if ($root1->getLateAssignFlag()) {
            $root1->resetLateAssignFlag();
            $this->setLateAssignFlag();
        }
        if ($root2->getLateAssignFlag()) {
            $root2->resetLateAssignFlag();
            $this->setLateAssignFlag();
        }

        return $this;
    }

    private function find() : Box
    {
        // find with path compression
        if ($this->parent !== $this) {
            $this->parent = $this->parent->find();
        }

        return $this->parent;
    }

    private function unionAll() : Box
    {
        foreach ($this as $box) {
            $this->link($box);
        }

        return $this;
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

}
