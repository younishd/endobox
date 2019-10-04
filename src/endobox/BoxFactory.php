<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2019 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

/**
 * Box factory that decides how to construct a Box based on the template's file extension.
 */
class BoxFactory
{

    private $paths = [];

    private $parsedown;

    /**
     * The factory will look into the given path for template files.
     */
    public function __construct(string $path, \Parsedown $parsedown)
    {
        $this->addFolder($path);
        $this->parsedown = $parsedown;
    }

    /**
     * Shortcut for create.
     */
    public function __invoke(string $template) : Box
    {
        return $this->create($template);
    }

    /**
     * Create a Box based on the given template and return it.
     */
    public function create(string $template) : Box
    {
        foreach ($this->paths as &$path) {
            // full path to template file without extension
            $file = \rtrim($path, '/') . '/' . \trim($template, '/');

            if (\file_exists($t = $file . '.php')) {
                $box = new Box(
                        new Template($t),
                        new EvalRendererDecorator(
                            new NullRenderer()));
                $this->assignDefaults($box);
                return $box;
            }

            if (\file_exists($t = $file . '.md.php')) {
                $box = new Box(
                        new Template($t),
                        new MarkdownRendererDecorator(
                            new EvalRendererDecorator(
                                new NullRenderer()), $this->parsedown));
                $this->assignDefaults($box);
                return $box;
            }

            if (\file_exists($t = $file . '.md')) {
                return new Box(
                        new Template($t),
                        new MarkdownRendererDecorator(
                            new NullRenderer(), $this->parsedown));
            }

            if (\file_exists($t = $file . '.html')) {
                return new Box(
                        new Template($t),
                        new NullRenderer());
            }
        }

        throw new \RuntimeException(\sprintf('Template "%s" not found.', $template));
    }

    /**
     * Add another folder to the list of template paths.
     */
    public function addFolder(string $path)
    {
        if (!\is_dir($path)) {
            throw new \RuntimeException(\sprintf('The path "%s" does not exist or is not a directory.', $path));
        }
        $this->paths[] = $path;
    }

    private function assignDefaults(Box $box) : Box
    {
        return $box->assign([
            'markdown' => function($md) {
                return new Box(
                    new Atom($md),
                    new MarkdownRendererDecorator(
                        new NullRenderer(), $this->parsedown));
            },
            'box' => function($t) use ($box) {
                $b = $this->create($t);
                $b->entangle($box);
                return $b;
            }
        ]);
    }

}
