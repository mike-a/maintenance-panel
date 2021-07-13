<?php
namespace Vivinet\MaintenancePanel\Http\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public function __construct(){

    }

    public function render()
    {
        return view('maintenance-panel::components.header');
    }
}
