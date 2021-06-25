<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 2:57 pm
 */

namespace Vivinet\MaintenancePanel\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MaintenancePanelSetup extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = "maintenance-panel:setup {action : the type of action to execute i.e. install_package, compile_project, dump, 'project_update'}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package setup management, (js and css install to core)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     * provide ability to perform three basic actions:
     * namely: load assets, unload assets and compile assets
     */
    public function handle()
    {
        $action = $this->arguments()['action'];

        if($action) {
            switch ($action) {
                case 'install_package':
                    $this->installPackage();
                    break;
                case 'dump':
                    $this->dump();
                    break;
                case 'update_project':
                    $this->updateProject();
                    break;
                case 'compile':
                    $this->compileProject();
                    break;
                default:
                    echo "unable to process given action";
            }
        } else {
            Log::info("Action not provided");

            echo "Please provide the necessary action";
        }
    }


    /**
     * install some given package
     */
    private function installPackage()
    {
        // @todo fix the functionality here
    }


    /**
     * run composer dump
     */
    private function dump()
    {
        $this->runShellOnCoreCommand('composer dump');

        echo "Dumped and autoloaded  successfully";
    }


    /**
     * run composer update
     */
    private function updateProject()
    {
        //dd("Are you here");
        $this->runShellOnCoreCommand('composer update');
        //dd("Are you dane!");
        echo "Project updated successfully";
    }


    /**
     * compile assets
     */
    private function compileProject()
    {
        $this->runShellOnCoreCommand('npm install && npm run dev');

        echo "Project assets compiled successfully";
    }

    /**
     * @param $command
     * run a shell command on the core
     */
    private function runShellOnCoreCommand($command)
    {
        try {
            $project_path = dirname(config_path());
            //$project_path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));

            $output = shell_exec('cd ' . $project_path . ' && ' . $command);
            //dd($output, $project_path, $command, __FILE__, config_path());
            Log::channel('daily')->info($output);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
