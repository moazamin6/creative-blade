<?php

use Illuminate\View\CreativeBlade;

require 'vendor/autoload.php';


$views = __DIR__ . '/demo/views';
$cache = __DIR__ . '/demo/cache';
$blade = new CreativeBlade($views, $cache);

echo $blade->view()->make('demo', ['message' => 'This is Creative Blade']);
