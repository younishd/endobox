<?php

require __DIR__ . '/../vendor/autoload.php';

use \Endobox\Box;
use \Endobox\MarkdownBox;
use \Endobox\EndlessPHPBox;
use \Endobox\Template;

class ExampleBox extends Box {
    
    protected function load()
    {   
        $foo = (new MarkdownBox())
                ->append_inner(new Template(__DIR__ . '/templates/foo.md'));
        
        $bar = (new EndlessPHPBox())
                ->append_inner(new Template(__DIR__ . '/templates/bar.php'));
        
        $qux = (new MarkdownBox())
                ->append_inner((new EndlessPHPBox())
                        ->append_inner(new Template(__DIR__ . '/templates/qux.md.php')));
        
        $this->append_inner(new Template(__DIR__ . '/templates/xyzzy.txt'));
        
        $qux->append($bar)->append($foo);
        
        $this->prepend_inner($qux);
    }
    
    protected function build($code)
    {
        return "<html>\n$code</html>\n";
    }

}


echo (new ExampleBox())->render();
