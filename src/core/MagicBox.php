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
 *     - '.md' files will be parsed as Markdown templates.
 *     - '.php' files will be evaluated as PHP templates.
 *     - '.md.php' files will first be evaluated as PHP, then parsed as Markdown templates.
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
        if (preg_match('/\.md\.php$/', $t)) {
            $mdbox = new MarkdownBox();
            $phpbox = new PHPBox();
            $phpbox->set_endless($this->endless);
            $phpbox->append_template($t);
            $mdbox->append_inner($phpbox);
            return $mdbox;
        }
        if (preg_match('/\.php$/', $t)) {
            $phpbox = new PHPBox();
            $phpbox->set_endless($this->endless);
            $phpbox->append_template($t);
            return $phpbox;
        }
        if (preg_match('/\.md$/', $t)) {
            $mdbox = new MarkdownBox();
            $mdbox->append_template($t);
            return $mdbox;
        }
        return new File($t);
    }

}
