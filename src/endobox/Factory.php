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
        $this->add_folder($path);
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

            $file = \rtrim($path, '/') . '/' . \trim($template, '/');

            if (\file_exists($t = $file . '.php')) {
                return new Box(new Template($t), new EvalRendererDecorator(new NullRenderer()));
            }

            if (\file_exists($t = $file . '.md.php')) {
                return new Box(new Template($t),
                    new MarkdownRendererDecorator(new EvalRendererDecorator(new NullRenderer())));
            }

            if (\file_exists($t = $file . '.mdx.php')) {
                return new Box(new Template($t),
                    new MarkdownExtraRendererDecorator(new EvalRendererDecorator(new NullRenderer())));
            }

            if (\file_exists($t = $file . '.md')) {
                return new Box(new Template($t), new MarkdownRendererDecorator(new NullRenderer()));
            }

            if (\file_exists($t = $file . '.mdx')) {
                return new Box(new Template($t), new MarkdownExtraRendererDecorator(new NullRenderer()));
            }

            if (\file_exists($t = $file . '.html')) {
                return new Box(new Template($t), new NullRenderer());
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
