<?php

namespace Illuminate\View;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Psr\Container\ContainerInterface;

class CreativeBlade
{
	protected $viewPaths;
	
	protected $cachePath;
	
	protected $app;
	
	public $extra;
	
	public function __construct($viewPaths, $cachePath, $extra = NULL)
	{
		
		$this->app = new Container;
		$this->viewPaths = (array)$viewPaths;
		$this->cachePath = $cachePath;
		$this->extra = $extra;
		
		$this->registerFilesystem();
		$this->registerEvents();
		
		$this->registerFactory();
		$this->registerViewFinder();
		$this->registerEngineResolver();
	}
	
	public function view()
	{
		return $this->app['view'];
	}
	
	public function registerFilesystem()
	{
		$this->app->bind('files', function()
		{
			return new Filesystem;
		});
	}
	
	public function registerEvents()
	{
		$this->app->bind('events', function()
		{
			return new Dispatcher;
		});
	}
	
	public function registerFactory()
	{
		$this->app->singleton('view', function($app)
		{
			// Next we need to grab the engine resolver instance that will be used by the
			// environment. The resolver will be used by an environment to get each of
			// the various engine implementations such as plain PHP or Blade engine.
			$resolver = $app['view.engine.resolver'];
			$finder = $app['view.finder'];
			$env = new Factory($resolver, $finder, $app['events']);
			// We will also set the container instance on this view environment since the
			// view composers may be classes registered in the container, which allows
			// for great testable, flexible composers for the application developer.
			$env->setContainer($app);
			
			$env->share('app', $app);
			
			return $env;
		});
	}
	
	public function registerViewFinder()
	{
		$me = $this;
		$this->app->bind('view.finder', function($app) use ($me)
		{
			return new FileViewFinder($app['files'], $me->viewPaths);
		});
	}
	
	public function registerEngineResolver()
	{
		$this->app->singleton('view.engine.resolver', function()
		{
			$resolver = new EngineResolver;
			// Next, we will register the various view engines with the resolver so that the
			// environment will resolve the engines needed for various views based on the
			// extension of view file. We call a method for each of the view's engines.
			foreach(['file', 'php', 'blade'] as $engine)
			{
				$this->{'register' . ucfirst($engine) . 'Engine'}($resolver);
			}
			return $resolver;
		});
	}
	
	public function registerFileEngine($resolver)
	{
		$resolver->register('file', function()
		{
			return new FileEngine;
		});
	}
	
	public function registerPhpEngine($resolver)
	{
		$resolver->register('php', function()
		{
			return new PhpEngine;
		});
	}
	
	public function registerBladeEngine($resolver)
	{
		$me = $this;
		// The Compiler engine requires an instance of the CompilerInterface, which in
		// this case will be the Blade compiler, so we'll first create the compiler
		// instance to pass into the engine so it can compile the views properly.
		$this->app->singleton('blade.compiler', function() use ($me)
		{
			return new BladeCompiler(
				$this->app['files'], $me->cachePath
			);
		});
		
		$resolver->register('blade', function()
		{
			return new CompilerEngine($this->app['blade.compiler'], $this->extra, $this->app['files']);
		});
	}
}