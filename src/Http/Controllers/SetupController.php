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
                'action' => 'required|in:install_package,dump,compile,update_project'
            ]);

            Artisan::call('engineers-console:setup', ['action' => $data['action']]);
        }

        return view('engineers-console::index');
    }


    /**
     * package setup
     */
    public function packageSetup()
    {
        $data = request()->validate([
            'package' => 'required',
            'action' => 'required|in:load_assets,unload_assets,compile,park,unplug,info,test'
        ]);

        $package_config = config('engineers-console.packages.' . $data['package']);

        Artisan::call($package_config['install_command'], ['action' => $data['action']]);

        return redirect()->back();
    }
}
