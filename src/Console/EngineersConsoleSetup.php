<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 2:57 pm
 */

namespace Vivinet\EngineersConsole\Console;


use Illuminate\Console\Command;

class EngineersConsoleSetup extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = "engineers-console:setup {action : the type of action to execute i.e. install_package, compile_project, dump, 'project_update'}";

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

        switch ($action) {
            case 'install_package':
                return $this->installPackage();
                break;
            case 'dump':
                return $this->dump();
                break;
            case 'update_project':
                return $this->updateProject();
                break;
            case 'compile':
                return $this->compileProject();
                break;
            default:
                return "Given action not configured yet";
        }
    }
}
