<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 2:07 pm
 */
namespace Vivinet\MaintenancePanel\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Vivinet\Basetheme\BasethemeServiceProvider;
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
        //dd(request()->all());
        if(request()->method() === 'POST')
        {
            $data = request()->validate([
                'action' => 'required|in:install_package,dump,compile,update_project',
                'package_name' => 'required_if:action,install_package',
                'url' => 'required_if:action,install_package',
                'source_type' => 'required_if:action,install_package',
                'install_command' => 'required_if:action,install_package',
            ]);
            //dd($data);

            if($data['action'] == 'install_package') {
                $this->repo->preparePackageInstallation($data);

                echo "Package installed successfully (added to config and json file updated to receive it)";
            } else {
                Artisan::call('maintenance-panel:setup', ['action' => $data['action']]);
            }
        }
        //This is used to keep the authentication process into the maintenance-panel stream
        cache(['previous-path'=>'maintenance-panel']);


        //check if the basetheme is loaded and return the correct view
        if(class_exists(BasethemeServiceProvider::class)){
            //Here we need to declare variables required by the base theme layout with their corresponding name
            $header_view = null;
            $right_side_view = null; //"maintenance-panel::components.right-side";
            $content_view = "maintenance-panel::components.content";
            $footer_view = null;

            return view('maintenance-panel::welcome', compact("header_view", "right_side_view", "content_view", "footer_view"));
        } else{
            return view("maintenance-panel::index");
        }

    }


    /**
     * package setup
     * @todo: check that the package is installed
     */
    public function packageSetup()
    {
        $data = request()->validate([
            'package' => 'required',
            'action' => 'required|in:load_assets,compile,park,unplug,info,test'
        ]);
        //dd($data);
        if($data['action'] === 'park')
        {
            $this->repo->installPackage($data);

            return redirect()->back();
        }  else {
            //dd("Donr");
            if($data['action'] === 'unplug') {
                //dd($data);
                $this->repo->unplugPackage($data);

            } else {
                $package_config = config('maintenance-panel.packages.' . $data['package']);
                //dd($package_config['install_command'], ['action' => $data['action']]);
                Artisan::call($package_config['install_command'], ['action' => $data['action']]);
            }

            return redirect()->back();
        }
    }
}
