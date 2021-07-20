<?php
/**
 * Created by PhpStorm
 * User: john
 * Date: 6/1/21
 * Time: 2:07 pm
 */
namespace Vivinet\MaintenancePanel\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Vivinet\Basetheme\BasethemeServiceProvider;
use Vivinet\MaintenancePanel\Http\Repositories\SetupRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class SetupController extends Controller
{
    protected $repo;

    public function __construct(SetupRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * return the setup and configurations page
     * @throws Exception
     */
    public function setup()
    {
        $return_message = "";
        //dd(request()->method());
        if(request()->method() === 'POST')
        {
            $data = request()->validate([
                'action' => 'required|in:install_package,dump,compile,update_project',
                // 'package_name' => 'required_if:action,install_package',
                'url' => 'required_if:action,install_package',
                // 'source_type' => 'required_if:action,install_package',
                // 'install_command' => 'required_if:action,install_package',
            ]);

            //Here Prepare the all required data to handle the package installation

            //Now separate the user and the repository name now


            if($data['action'] == 'install_package') {
                $repo_info = explode("/", $data['url']);

                $github_username = $repo_info[3]??"";
                $repository_name = preg_replace("/(.git)/", "", $repo_info[4]??"");
                //Send the request to get the real name from the github
                $target_github_api = "https://api.github.com/repos/".$github_username."/".$repository_name."/contents/composer.json";
                $github_token = config('maintenance-panel-auth.github_token');

                //dd($github_token);
                $github_client = new Client();
                try{
                    $github_request = $github_client->request(
                        'GET',
                        $target_github_api,
                        [
                            'headers' => [
                                'Authorization' => "Bearer ".$github_token,
                            ]
                        ]
                    );

                    if($github_request->getStatusCode() == 200){
                        //Here we have the response we need
                        $github_response = json_decode($github_request->getBody());

                        $composer_content = json_decode(base64_decode($github_response->content));

                        //dd($composer_content);
                        $package_name = explode("/",$composer_content->name)[1]??"";
                        $data['package_name'] = $package_name;
                        $data['source_type'] = "vcs";
                        $data['install_command'] = $package_name.":setup";
                        //dd($data);
                    } else {
                        //dd($github_request);
                        //Here some errors occurs here.
                        Log::channel('daily')->info("Package can't be loaded");
                    }
                } catch(GuzzleException $e){
                    //throw new Exception($e->getMessage());
                    Log::channel("daily")->error($e->getMessage());
                }
                //dd($data, $repo_info,$github_username,$repository_name);
                $this->repo->preparePackageInstallation($data);

                //Here make sure to run script used to run the pack operation before
                $this->repo->installPackage(['package' => $data['package_name'],'action' => 'park',]);

                $return_message = "Package installed successfully (added to config and json file updated to receive it)";
            } else {
                Artisan::call('maintenance-panel:setup', ['action' => $data['action']]);
            }
        }
        //This is used to keep the authentication process into the maintenance-panel stream
        cache(['previous-path'=>'maintenance-panel']);


        //check if the base theme is loaded and return the correct view
        if(class_exists(BasethemeServiceProvider::class)){
            //Here we need to declare variables required by the base theme layout with their corresponding name
            $header_view = null;
            $right_side_view = null; //"maintenance-panel::components.right-side";
            $content_view = "maintenance-panel::components.content";
            $footer_view = null;

            return view('maintenance-panel::welcome', compact("header_view", "right_side_view", "content_view", "footer_view", "return_message"));
        } else{
            return view("maintenance-panel::index", compact("return_message"));
        }

    }


    /**
     * package setup
     * @todo: check that the package is installed
     */
    public function packageSetup() : RedirectResponse
    {
        $data = request()->validate([
            'package' => 'required',
            'action' => 'required|in:load_assets,compile,park,unplug,info,test'
        ]);
        //dd($data);
        if("park" === $data['action'])
        {
            //dd($data);
            /**
             * Here Now the park operation got reversed information
             *
             */
            $data['action'] = 'unplug';
            $this->repo->unplugPackage($data);
            //$this->repo->installPackage($data);

            return redirect()->back();
        }  else {
            if($data['action'] === 'unplug')
            {
                $this->repo->unplugPackage($data);

            }
            else
            {
                //dd($data);
                $package_config = config('maintenance-panel.packages.' . $data['package']);
                //dd($package_config['install_command'], ['action' => $data['action']]);
                Artisan::call($package_config['install_command'], ['action' => $data['action']]);

                //Here make sure to update the maintenance panel configuration data
                switch ($data['action']){
                    case 'compile':
                        /**
                         * Here Make sure to setup the maintenance panel to track the installed package
                         */
                        $package_installed = config('maintenance-panel.packages.'.$data['package']);

                        /**
                         * Now Make the installation flag true
                         */
                        $package_installed['installed'] = true;
                        $package_installed['assets'] = 'loaded';

                        config(['maintenance-panel.packages.' . $data['package'] => $package_installed]);

                        //Set the configuration information
                        $text = "<?php return " . var_export(config('maintenance-panel'), true);

                        $config_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/maintenance-panel.php';

                        file_put_contents($config_path, $text);
                        //dd($package_installed);
                        break;
                    default:

                }
            }

            return redirect()->back();
        }
    }
}
