<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 5/30/21
 * Time: 4:00 pm
 */

namespace Vivinet\EngineersConsole\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}

