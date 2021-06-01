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
                'action' => 'required|in:install_package,dum,compile,update_project'
            ]);

            Artisan::call('engineers-console:setup', ['action' => $data['action']]);
        }

        return view('engineers-console::index');
    }
}
