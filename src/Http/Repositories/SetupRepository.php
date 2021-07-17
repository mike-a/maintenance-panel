<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/3/21
 * Time: 12:23 am
 */

namespace Vivinet\MaintenancePanel\Http\Repositories;


use Illuminate\Support\Facades\Artisan;

class SetupRepository
{
    public function installPackage($data)
    {
        //dd($data, $package_config);
        $package = config('maintenance-panel.packages')[$data['package']];

        // write to composer.json
        if(!command_exists($package['install_command'])) {
            $project_composer_file = dirname(config_path()) . '/composer.json';

            $json = json_decode(file_get_contents($project_composer_file), true);

            //Here make sure not to add single row multiple times
            $check_require = $this->verify_before_add_require($json['require'], 'vivinet/' . $data['package']);
            if (!$check_require) {
                if ($package['source_type'] === 'path') {
                    $json['require']['vivinet/' . $data['package']] = '@dev';
                } else {
                    $json['require']['vivinet/' . $data['package']] = 'dev-master';
                }
            }

            //As repositories are private we need to add them in the repository sections
            //check if the  entry is not the array
            $check_repository = $this->verify_before_add_repository($json['repositories'], $package['url']);
            //dd($package, $check_repository,"check point 1");
            if (!$check_repository) {
                $json['repositories'][] = (object)[
                    'type' => $package['source_type'],
                    'url' => $package['url']
                ];
            }
            //dd($json);
            file_put_contents($project_composer_file, json_encode($json, JSON_PRETTY_PRINT));
        }
        $this->dumpAndUpdateProject($data['package']);
    }

    private function verify_before_add_require($requires, $required) : bool {
        if(array_key_exists($required, $requires))
            return true;
        return false;
    }

    private function verify_before_add_repository($repos, $name) : bool{
        //dd($repos, $name);
        $pattern = "#(".$name.")#";
        //echo $pattern;
        foreach($repos AS $repo){
            if(preg_match($pattern, $repo['url'])){
                //dd("Found Here then");
                return true;
            }
        }
        return false;
    }

    /**
     * @param $data
     * basically remove the package assets
     * and not require it in composer.json
     * and thereafter run composer update
     */
    public function unplugPackage($data)
    {
        //dd($data);
        //unload package assets
        $package_config = config('maintenance-panel.packages.' . $data['package']);

        Artisan::call($package_config['install_command'], ['action' =>'unload_assets']); // Every package should define this command to unload its own files from core assets and into the main

        // remove from composer.json
        $project_composer_file =dirname(config_path()) . '/composer.json';
        $json = json_decode(file_get_contents($project_composer_file), true);

        unset($json['require']['vivinet/' . $data['package']]);
        file_put_contents($project_composer_file, json_encode($json, JSON_PRETTY_PRINT));

        //dump and update project
        $this->dumpAndUpdateProject($data['package']);

        //dd($json['require'], $data['package'], Artisan::all());
    }

    /**
     * dump and compile the project
     * @param $package
     */
    public function dumpAndUpdateProject($package)
    {
        //dd($package);
        $remove_package_vendor = shell_exec('cd ' . dirname(config_path()) . '/vendor/vivinet && ' . ' rm -rf ' . $package);
        //dd($remove_package_vendor);
        // update project
        Artisan::call('maintenance-panel:setup', ['action' => 'update_project']);
        // dump on project
        Artisan::call('maintenance-panel:setup', ['action' => 'dump']);

        //Here the Project should be refreshed after being updated
    }


    /**
     * prepare package installation
     *
     * 1. we update the config file first,
     * 2. then let's update the composer json to accept the url
     */
    public function preparePackageInstallation($data)
    {
        $packages = config('maintenance-panel.packages');
        //Here Check the error
        $this->addToPanelConfigFile($data, $packages);

        $this->addPackageToComposerJson($data);
    }

    /**
     * @param $data
     * @param $packages
     */
    private function addToPanelConfigFile($data, $packages)
    {
        //dd($data, $packages);
        if(array_key_exists($data['package_name'], $packages)) {
            abort(422, "Package already exists");
        } else {
            config(['maintenance-panel.packages.' . $data['package_name'] => [
                'installed' => 'false',
                'assets' => 'unloaded',
                'source_type' => $data['source_type'],
                'url' => $data['url'],
                'install_command' => $data['install_command']
            ]]);

            $text = '<?php return ' . var_export(config('maintenance-panel'), true) . ';';

            $config_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/config/maintenance-panel.php';

            file_put_contents($config_path, $text);
        }
    }

    /**
     * @param $data
     */
    private function addPackageToComposerJson($data)
    {
        // write to composer.json
        $project_composer_file =dirname(config_path()) . '/composer.json';

        $json = json_decode( file_get_contents($project_composer_file), true);

        if(!array_key_exists('repositories', $json)) {
            $json['repositories'] = [];
        }

        //check if the repository were added before
        if(!$this->verify_before_add_repository($json['repositories'], $data['url'])){
            array_push($json['repositories'], [
                'type' => $data['source_type'],
                'url' => $data['url']
            ]);
        }

        if(!$this->verify_before_add_require($json['require'], "vivinet/" . $data['package_name'])){

            if($data['source_type'] === 'path') {
                $json['require']['vivinet/' . $data['package_name']] = '@dev';
            } else {
                $json['require']['vivinet/' . $data['package_name']] = 'dev-master';
            }

        }

        file_put_contents($project_composer_file, json_encode($json, JSON_PRETTY_PRINT));
    }
}
