<?php

require __DIR__ . '/vendor/autoload.php';

use CreativeBlade\PhpBlade\PhpBlade;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new PhpBlade($views, $cache);

echo $blade->view()->make('test', ['name' => 'Moaz Amin']);
