Creative PHP Blade Template Engine
=====

Full compatible to every php application standalone laravel's blade modify on illuminate/view v6.10.0

Installation
------------

Install using composer:

```bash
composer require moazamin6/creative-blade
```

Usage
-----

Create a Blade instance by passing it the folder(s) where your view files are located, and a cache folder. Render a template by calling the `make` method. More information about the Blade templating engine can be found on https://laravel.com/docs/7.x/blade.

```php
require 'vendor/autoload.php';

use CreativeBlade\CreativeBlade;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';

$blade = new CreativeBlade($views, $cache);

echo $blade->view()->make('demo', ['message' => 'This is Creative Blade']);
```
In every other standalone blade template package there is a problem that you can not use $this keyword to access your native application features for example if you want to implement blade template in your existing codeigniter application in views files you access your sessions like 
`$this->session` if you convert your views to blade then your application will crash in this scenario so I modify actual `illuminate/view` package of laravel to solve this problem and I also use existing standalone package `coolpraz/php-blade` so here you can send you data object that you want to access in view like this
I will use codeigniter default instance 
```php
$ci = &get_instance();
$blade = new CreativeBlade($views, $cache,$ci);
```

Now you can easily create a directive by calling the ``compiler()`` function

```php
$blade->compiler()->directive('datetime', function ($expression) {
    return "<?php echo with({$expression})->format('F d, Y g:i a'); ?>";
});

{{-- In your Blade Template --}}
<?php $dateObj = new DateTime('2017-01-01 23:59:59') ?>
@datetime($dateObj)
```

The Blade instances passes all methods to the internal view factory. So you can use all blade features as described in the [Blade documentation](http://laravel.com/docs/5.3/views), please visit site for more information.

Integrations
-----
You can use PHP Blade with any framework, vanilla php script or can be use developing any plugins for CMS.
