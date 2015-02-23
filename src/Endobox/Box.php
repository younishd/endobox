<?php

/*
 * This file is part of Endobox.
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
     * @var \Endobox\Renderable Sorted array (linked list) of renderable objects that have been
     * appended or prepended to this box.
     */
    private $renderables = [];
    
    /**
     * @var string The rendered code.
     */
    protected $code = '';

    /**
     * Load the box, i.e., initialize the renderables. This method gets executed before rendering.
     * This is where you normally append the renderable objects to this box or assign the data.
     */
    protected abstract function load();
    
    /**
     * This method gets executed after rendering, so the code attribute will be set and it is possible to apply
     * some modifications to the code to finalize the render process (e.g., parsing, wrapping).
     */
    protected abstract function build();
    
    /**
     * Cast to string.
     * @return string Result of render.
     */
    public function __toString()
    {
        return $this->render();
    }
    
    /**
     * Append a renderable object to this box.
     * @param \Endobox\Renderable Some renderable object.
     */
    protected function append(Renderable $r)
    {
        $this->renderables[] = $r;
        return $this;
    }
    
    /**
     * Prepend a renderable object to this box.
     * @param \Endobox\Renderable Some renderable object.
     */
    protected function prepend(Renderable $r)
    {
        \array_unshift($this->renderables, $r);
        return $this;
    }
    
    /**
     * Render this box and return the code.
     * @return string Rendered code.
     */
    public function render()
    {
        $this->load();
        foreach ($this->renderables as $r) {
            $this->code .= $r->render();
        }
        $this->build();
        return $this->code;
    }
    
}
