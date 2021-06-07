<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 2:07 pm
 */
namespace Vivinet\MaintenancePanel\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Vivinet\MaintenancePanel\Http\Repositories\SetupRepository;

class SetupController extends Controller
{
    protected $repo;

    public function __construct(SetupRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * return the setup and configurations page
     */
    public function setup()
    {
        if(request()->method() === 'POST')
        {
            $data = request()->validate([
                'action' => 'required|in:install_package,dump,compile,update_project',
                'package_name' => 'required_if:action,install_package',
                'url' => 'required_if:action,install_package',
                'source_type' => 'required_if:action,install_package',
                'install_command' => 'required_if:action,install_package',
            ]);


            if($data['action'] == 'install_package') {
                $this->repo->preparePackageInstallation($data);

                echo "Package installed successfully (added to config and json file updated to receive it)";
            } else {
                Artisan::call('maintenance-panel:setup', ['action' => $data['action']]);
            }
        }

        return view('maintenance-panel::index');
    }


    /**
     * package setup
     * @todo: check that the package is installed
     */
    public function packageSetup()
    {
        $data = request()->validate([
            'package' => 'required',
            'action' => 'required|in:load_assets,compile_package,plug_in,unplug,info,test'
        ]);

        if($data['action'] === 'plug_in')
        {
            $this->repo->installPackage($data);

            return redirect()->back();
        }  else {

            if($data['action'] === 'unplug') {
                $this->repo->unplugPackage($data);

            } else {
                $package_config = config('mp-packages.' . $data['package']);

                Artisan::call($package_config['install_command'], ['action' => $data['action']]);
            }

            return redirect()->back();
        }
    }
}
