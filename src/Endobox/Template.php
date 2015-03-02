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
 * A template renderable is basically used to read in some template text files
 * that will get parsed later (e.g., Markdown).
 * 
 * @author YouniS Bensalah <younis.bensalah@riseup.net>
 */
class Template implements Renderable {
    
    private $filename = '';

    /**
     * @param string $filename Path to template file.
     */
    public function __construct($filename)
    {
        $this->filename = (string)$filename;
    }
    
    /**
     * Return the content of the template file.
     * 
     * @return string Content of template file.
     * @throws \InvalidArgumentException if the template file does not exist.
     */
    public function render()
    {
        if (!\file_exists($this->filename)) {
            throw new \InvalidArgumentException(\sprintf('Template file %s does not exist.', $this->filename));
        }
        return \file_get_contents($this->filename);
    }
    
}
