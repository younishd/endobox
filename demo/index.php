<?php

require __DIR__ . '/../vendor/autoload.php';

$factory = endobox\Endobox::create(__DIR__ . '/templates');

// omit extension
$box = $factory('hello');

// assign data directly via render
echo $box->render( ['subject' => 'world'] );
