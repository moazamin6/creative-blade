<?php

namespace CreativeBlade;
require 'vendor/autoload.php';
class Blade
{
	public $views = __DIR__ . '\test\views';
	public $cache = __DIR__ . '\test\cache';
	public $blade;
	
	public function __construct()
	{
		
		$this->blade = new CreativeBlade($this->views, $this->cache);
	}
	
	public function loadBladeView($view, $data = [])
	{
		echo $this->blade->view()->make($view, $data);
		exit;
	}
}


$blade = new Blade();
$blade->loadBladeView('demo');