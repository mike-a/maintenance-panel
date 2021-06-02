<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 2:07 pm
 */
namespace Vivinet\EngineersConsole\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class SetupController extends Controller
{
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
                $this->preparePackageInstallation($data);

                echo "Package installed successfully (added to config and json file updated to receive it)";
            } else {
                Artisan::call('engineers-console:setup', ['action' => $data['action']]);
            }
        }

        return view('engineers-console::index');
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

        if($data['action'] === 'park')
        {
            $this->installPackage($data);

            return redirect()->back();
        }  else {

            if($data['action'] === 'unplug') {
                $data['action'] = 'unload_assets';
            }

            $package_config = config('engineers-console.packages.' . $data['package']);

            $output = Artisan::call($package_config['install_command'], ['action' => $data['action']]);

            if($data['action'] === 'unload_assets')
            {
                $this->unplugPackage($data);
            }

            return redirect()->back()->with([
                'output' => $output
            ]);
        }
    }

    public function installPackage($data)
    {
        $packages = config('engineers-console.packages');

        $package = $packages[$data['package']];

        // write to composer.json
        $project_composer_file =dirname(config_path()) . '/composer.json';

        $json = json_decode( file_get_contents($project_composer_file), true);

        if($package['source_type'] === 'path') {
            $json['require']['vivinet/' . $data['package']] = '@dev';
        } else {
            $json['require']['vivinet/' . $data['package']] = 'dev-master';
        }

        file_put_contents($project_composer_file, json_encode($json));

        $this->dumpAndCompileProject();
    }

    /**
     * @param $data
     * basically remove the package assets(done prior)
     * and now all we have to do is not require it in composer.json
     */
    public function unplugPackage($data)
    {
        $project_composer_file =dirname(config_path()) . '/composer.json';

        $json = json_decode( file_get_contents($project_composer_file), true);

        unset($json['require']['vivinet/' . $data['package']]);

        file_put_contents($project_composer_file, json_encode($json));

        $this->dumpAndCompileProject();
    }

    /**
     * dump and compile the project
     */
    public function dumpAndCompileProject()
    {
        // dump on project
        Artisan::call('engineers-console:setup', ['action' => 'dump']);
        // compile project
        Artisan::call('engineers-console:setup', ['action' => 'compile']);
    }


    /**
     * prepare package installation
     *
     * 1. we update the config file first,
     * 2. then let's update the composer json to accept the url
     */
    public function preparePackageInstallation($data)
    {
        $packages = config('engineers-console.packages');

        if(array_key_exists($data['package_name'], $packages)) {
            abort(422, "Package already exists");
        } else {
            config(['engineers-console.packages.' . $data['package_name'] => [
                'installed' => 'false',
                'source_type' => $data['source_type'],
                'url' => $data['url'],
                'install_command' => $data['install_command']
            ]]);

            $text = '<?php return ' . var_export(config('engineers-console'), true) . ';';

            $config_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/engineers-console.php';

            file_put_contents($config_path, $text);
        }

        // write to composer.json
        $project_composer_file =dirname(config_path()) . '/composer.json';

        $json = json_decode( file_get_contents($project_composer_file), true);

        if(!array_key_exists('repositories', $json)) {
            $json['repositories'] = [];
        }

        array_push($json['repositories'], [
            'type' => $data['source_type'],
            'url' => $data['url']
        ]);

        if($data['source_type'] === 'path') {
            $json['require']['vivinet/' . $data['package_name']] = '@dev';
        } else {
            $json['require']['vivinet/' . $data['package_name']] = 'dev-master';
        }

        file_put_contents($project_composer_file, json_encode($json));
    }
}
