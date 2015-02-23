<?php

require __DIR__ . '/../vendor/autoload.php';

use \Endobox\Box;
use \Endobox\Template;
use \Endobox\PHPBox;
use \Endobox\MarkdownBox;

class Example extends Box {
    
    protected function load()
    {
        $this->append((new MarkdownBox())
            ->append((new PHPBox())
            ->append(new Template(__DIR__ . '/fubar.md.php'))));
    }

    protected function build() {}

}

$fubar = new Example();
echo $fubar->render();
