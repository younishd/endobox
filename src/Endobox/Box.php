<?php

/*
 * This file is part of endobox.
 * 
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Endobox;

/**
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
abstract class Box implements Renderable {
    
    /**
     * @var array Linked list of inner renderable objects.
     */
    private $interior = [];
            
    /**
     * @var \Endobox\Box $next Next outer box object.
     * @var \Endobox\Box $prev Previous outer box object.
     */
    private $next = null;
    private $prev = null;
    
    /**
     * Do stuff before inner rendering.
     * 
     * This callback method gets executed right BEFORE the inner rendering of the box.
     * It is where you normally append or prepend the inner renderable objects to this box.
     * 
     * The default load method does nothing.
     */
    protected function load() {}
    
    /**
     * Do stuff after inner rendering.
     * 
     * This callback method gets executed right AFTER the inner rendering of the box.
     * It allows you to alter the rendered inner code before finally returning it to the caller.
     * So this callback method takes the rendered code as argument and is supposed to return the altered version of it.
     * E.g., implementing some kind of parser or wrapper function.
     * 
     * The default build method just returns the code argument as is.
     * 
     * @param string $code The rendered inner code.
     * @return string $code The modified code.
     */
    protected function build($code)
    {
        return $code;
    }
    
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * Render everything and return the code.
     * 
     * @return string The final code.
     */
    public function render()
    {
        $code = '';
        for ($b = $this->head(); $b !== null; $b = $b->next) {
            $code .= $b->render_inner();
        }
        return $code;
    }
    
    /**
     * Render the inner code and return it.
     * 
     * @return string The inner code.
     */
    public function render_inner()
    {
        $code = '';
        $this->load();
        foreach ($this->interior as $r) {
            $code .= $r->render();
        }
        return $this->build($code);
    }
    
    /**
     * Append a box to the end of the outer linked list.
     * 
     * @param \Endobox\Box $b The box to be added.
     * @return \Endobox\Box The tail of the initial linked list
     * (i.e., the box instance whose next reference points to the passed box).
     */
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
    
    /**
     * Prepend a box to the beginning of the outer linked list.
     * 
     * @param \Endobox\Box $b The box to be added.
     * @return \Endobox\Box The head of the initial linked list
     * (i.e., the box instance whose prev reference points to the passed box).
     */
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
    
    /**
     * Append a renderable object to the end of the inner list.
     * 
     * @param \Endobox\Renderable $r The renderable object to be added.
     * @return \Endobox\Box This very instance.
     */
    protected function append_inner(Renderable $r)
    {
        $this->interior[] = $r;
        return $this;
    }
    
    /**
     * Prepend a renderable object to the beginning of the inner list.
     * 
     * @param \Endobox\Renderable $r The renderable object to be added.
     * @return \Endobox\Box This very instance.
     */
    protected function prepend_inner(Renderable $r)
    {
        \array_unshift($this->interior, $r);
        return $this;
    }
    
    /**
     * Rewind and return first box of the outer linked list.
     * 
     * @return \Endobox\Box The head box.
     */
    public function head()
    {
        for ($b = $this; $b->prev !== null; $b = $b->prev);
        return $b;
    }
    
    /**
     * Fast-forward and return last box of the outer linked list.
     * 
     * @return \Endobox\Box The tail box.
     */
    public function tail()
    {
        for ($b = $this; $b->next !== null; $b = $b->next);
        return $b;
    }

}
