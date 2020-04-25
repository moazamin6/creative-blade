<?php
require 'vendor/autoload.php';

use CreativeBlade\CreativeBlade;

$views = __DIR__ . '/demo/views';
$cache = __DIR__ . '/demo/cache';

$blade = new CreativeBlade($views, $cache);

echo $blade->view()->make('demo', ['message' => 'This is Creative Blade']);
