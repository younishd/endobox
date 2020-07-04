<?php

declare(strict_types = 1);

/**
 * This file is part of endobox.
 *
 * (c) 2015-2020 Younis Bensalah <younis.bensalah@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox;

class BoxFactory
{

    private $paths = [];

    private $parsedown;

    public function __construct(string $path, \Parsedown $parsedown)
    {
        $this->addFolder($path);
        $this->parsedown = $parsedown;
    }

    public function __invoke(string $template) : Box
    {
        return $this->create($template);
    }

    public function create(string $template) : Box
    {
        foreach ($this->paths as $path) {
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
                $box = new Box(
                        new Template($t),
                        new MarkdownRendererDecorator(
                            new NullRenderer(), $this->parsedown));
                $this->assignDefaults($box);
                return $box;
            }

            if (\file_exists($t = $file . '.html')) {
                $box = new Box(
                        new Template($t),
                        new NullRenderer());
                $this->assignDefaults($box);
                return $box;
            }
        }

        throw new \RuntimeException(\sprintf('Template "%s" not found.', $template));
    }

    public function addFolder(string $path)
    {
        if (!\is_dir($path)) {
            throw new \RuntimeException(\sprintf('The path "%s" does not exist or is not a directory.', $path));
        }
        $this->paths[] = $path;
    }

    private function assignDefaults(Box $box) : Box
    {
        $defaults = [];

        $defaults['markdown'] = $defaults['m'] = function($md) {
            return new Box(
                new Atom($md),
                new MarkdownRendererDecorator(
                    new NullRenderer(), $this->parsedown));
        };

        $defaults['escape'] = $defaults['e'] = function($var) {
            return \htmlspecialchars($var);
        };

        $defaults['box'] = $defaults['b'] = function($t) use ($box) {
            $b = $this->create($t);
            $b->link($box);
            return $b;
        };

        return $box->assign($defaults);
    }

}
