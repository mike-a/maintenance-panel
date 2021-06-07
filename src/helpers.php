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
        try {
            Artisan::call($name . ' test_install');

            return true;
        } catch (\Symfony\Component\Console\Exception\CommandNotFoundException $e) {

            return false;
        }
    }
}


if(!function_exists('runCommand')) {
    /**
     * @param $command
     * run a shell command on the core
     */
    function runCommand($command) {
        $output = array();
        $retVal = null;
        exec($command, $output, $retVal );

        if ($retVal != 0)
        {
            dump($output);
            Log::error($output);
        }
    }
}
