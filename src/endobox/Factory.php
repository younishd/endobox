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
 * A Box factory that decides how to render a template based on their file extension.
 */
class Factory
{

    private $paths = [];

    /**
     * Construct a Factory that looks into the given path for template files.
     */
    public function __construct(string $path)
    {
        if (!\is_dir($path)) {
            throw new \RuntimeException(\sprintf('The path "%s" does not exist or is not a directory.', $path));
        }
        $this->paths[] = $path;
    }

    /**
     * Shortcut for make().
     */
    public function __invoke(string $template) : Box
    {
        return $this->make($template);
    }

    /**
     * Make a Box based on the given template and return it.
     */
    public function make(string $template) : Box
    {

    }

    /**
     * Add another folder to the list of template paths.
     */
    public function add_folder(string $path)
    {
        if (!\is_dir($path)) {
            throw new \RuntimeException(\sprintf('The path "%s" does not exist or is not a directory.', $path));
        }
        $this->paths[] = $path;
    }

}
