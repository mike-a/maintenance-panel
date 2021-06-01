<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 1:53 pm
 */
namespace Vivinet\EngineersConsole;

use Illuminate\Support\ServiceProvider;
use Vivinet\EngineersConsole\Console\EngineersConsoleSetup;

class EngineersConsoleServiceProvider extends ServiceProvider
{
    /**
     * hook services into the service container
     */
    public function boot()
    {
        $this->routes();
        $this->views();
        $this->loadCommands();
    }

    /**
     * register into the service container
     */
    public function register()
    {
        include_once(__DIR__.'/helpers.php');
        $this->mergeConfigFrom(__DIR__ . '/../config/engineers-console.php', 'engineers-console');
    }

    /**
     * routes
     */
    private function routes()
    {
        require __DIR__.'/Http/routes/web.php';
    }

    /**
     * package views
     */
    private function views()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'engineers-console');
    }

    /**
     * load package commands
     */
    private function loadCommands()
    {
        $this->commands([
            EngineersConsoleSetup::class
        ]);
    }
}
