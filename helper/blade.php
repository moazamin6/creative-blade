<?php


use CreativeBlade\CreativeBlade;

if (!function_exists('view')) {

    function view(CreativeBlade $instance, $view = null, $data = [], $mergeData = [])
    {
        $factory = $instance->view();
        if (func_num_args() === 1) {
            return $factory;
        }
        return $factory->make($view, $data, $mergeData);
    }
}
