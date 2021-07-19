<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 1:53 pm
 */
namespace Vivinet\MaintenancePanel;

use Illuminate\Support\ServiceProvider;
use Vivinet\MaintenancePanel\Console\MaintenancePanelSetup;
use Vivinet\MaintenancePanel\Http\View\Components\Content;
use Vivinet\MaintenancePanel\Http\View\Components\Header;
use Vivinet\MaintenancePanel\Http\View\Components\RightSide;

class MaintenancePanelServiceProvider extends ServiceProvider
{
    /**
     * hook services into the service container
     */
    public function boot()
    {
        $this->routes();
        $this->views();
        $this->loadCommands();

        $this->loadViewComponentsAs('maintenance-panel', [
            Header::class,
            RightSide::class,
            Content::class
        ]);
    }

    /**
     * register into the service container
     */
    public function register()
    {
        include_once(__DIR__.'/helpers.php');
        $this->mergeConfigFrom(__DIR__ . '/../config/maintenance-panel.php', 'maintenance-panel');
        $this->mergeConfigFrom(__DIR__ . '/../config/maintenance-panel-auth.php', 'maintenance-panel-auth');
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'maintenance-panel');
    }

    /**
     * load package commands
     */
    private function loadCommands()
    {
        $this->commands([
            MaintenancePanelSetup::class
        ]);
    }
}
