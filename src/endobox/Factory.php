<?php

/**
 * This file is part of endobox.
 *
 * (c) 2015-2017 YouniS Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

use \Pimple\Container;

/**
 * A Box factory that decides how to render a template based on its file extension.
 */
class Factory
{

    private $paths = [];

    private $container;

    /**
     * Construct a Factory that looks into the given path for template files.
     */
    public function __construct(string $path, Container $container)
    {
        $this->add_folder($path);

        $this->container = $container;

        $this->container['parsedown'] = function () {
            return new \Parsedown();
        };
        $this->container['parsedown_extra'] = function () {
            return new \ParsedownExtra();
        };

        // Notice how we are using the same NullRenderer instance everywhere, because it has no state.
        $this->container['null_renderer'] = function () {
            return new NullRenderer();
        };
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
        foreach ($this->paths as &$path) {
            // full path to template file without extension
            $file = \rtrim($path, '/') . '/' . \trim($template, '/');

            if (\file_exists($t = $file . '.php')) {
                return new Box(new Template($t), new EvalRendererDecorator($this->container['null_renderer']));
            }

            if (\file_exists($t = $file . '.md.php')) {
                return new Box(new Template($t), new MarkdownRendererDecorator(new EvalRendererDecorator(
                        $this->container['null_renderer']), $this->container['parsedown']));
            }

            if (\file_exists($t = $file . '.mdx.php')) {
                return new Box(new Template($t), new MarkdownRendererDecorator(new EvalRendererDecorator(
                        $this->container['null_renderer']), $this->container['parsedown_extra']));
            }

            if (\file_exists($t = $file . '.md')) {
                return new Box(new Template($t), new MarkdownRendererDecorator(
                        $this->container['null_renderer'], $this->container['parsedown']));
            }

            if (\file_exists($t = $file . '.mdx')) {
                return new Box(new Template($t), new MarkdownRendererDecorator(
                        $this->container['null_renderer'], $this->container['parsedown_extra']));
            }

            if (\file_exists($t = $file . '.html')) {
                return new Box(new Template($t), $this->container['null_renderer']);
            }
        }

        throw new \RuntimeException(\sprintf('Template "%s" not found.', $template));
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
