<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 1:54 pm
 */
if (!function_exists('command_exists')) {
    /**
     * @return bool
     * check if the package assets are loaded up to the core
     */
    function command_exists($name)
    {
        return
            array_key_exists($name, \Illuminate\Support\Facades\Artisan::all());
    }
}
