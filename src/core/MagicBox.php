<?php

/*
 * This file is part of endobox.
 *
 * (c) 2015 YouniS Bensalah <younis.bensalah@riseup.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace endobox\core;

/**
 * A MagicBox allows you to dynamically append or prepend PHP, Markdown, or plain text templates,
 * as well as a combination of both PHP and Markdown.
 *
 * The template type will be determined by the template file extension:
 *
 *     - '.md[x]' files will be parsed as Markdown [Extra] templates.
 *     - '.php' files will be evaluated as PHP templates.
 *     - '.md[x].php' files will first be evaluated as PHP, then parsed as Markdown [Extra] templates.
 *     - Anything else will be returned as is (i.e., plain text).
 *
 * Of course you're able to assign data to this box which will then be accessible to all PHP templates.
 * That's why it's called a magic box...
 */
class MagicBox extends TemplateBox {

    private $endless = false;

    public function set_endless($endless = true)
    {
        $this->endless = (bool)$endless;
    }

    public function append_template($t)
    {
        return $this->append_inner($this->get_box($t));
    }

    public function prepend_template($t)
    {
        return $this->prepend_inner($this->get_box($t));
    }

    private function get_box($t)
    {
        // matches /\.md\.php$/
        if (\substr(\strrev($t), 0, 7) === 'php.dm.') {
            $mdbox = new MarkdownBox();
            $phpbox = new PHPBox($this->data);
            $phpbox->set_endless($this->endless);
            $phpbox->append_template($t);
            $mdbox->append_inner($phpbox);
            return $mdbox;
        }

        // matches /\.mdx\.php$/
        if (\substr(\strrev($t), 0, 8) === 'php.xdm.') {
            $mdxbox = new MarkdownExtraBox();
            $phpbox = new PHPBox($this->data);
            $phpbox->set_endless($this->endless);
            $phpbox->append_template($t);
            $mdxbox->append_inner($phpbox);
            return $mdxbox;
        }

        // matches /\.php$/
        if (\substr(\strrev($t), 0, 4) === 'php.') {
            $phpbox = new PHPBox($this->data);
            $phpbox->set_endless($this->endless);
            $phpbox->append_template($t);
            return $phpbox;
        }

        // matches /\.md$/
        if (\substr(\strrev($t), 0, 3) === 'dm.') {
            $mdbox = new MarkdownBox();
            $mdbox->append_template($t);
            return $mdbox;
        }

        // matches /\.mdx$/
        if (\substr(\strrev($t), 0, 4) === 'xdm.') {
            $mdxbox = new MarkdownExtraBox();
            $mdxbox->append_template($t);
            return $mdxbox;
        }

        // default to plain text
        return new File($t);
    }

}
