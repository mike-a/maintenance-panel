<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/7/21
 * Time: 10:41 pm
 */

namespace Vivinet\MaintenancePanel\Http\Interfaces;


interface PackageSetupInterface
{

    /**
     * @return mixed
     * of course your artisan command to run needs this
     */
    function handle();


    /**
     * @return mixed
     * copy package assets to the core
     */
    function loadAssets();

    /**
     * @return mixed
     * remove the copied assets from the core
     */
    function unloadAssets();

    /**
     * @return mixed
     * after the assets are copied over to the project
     * we want to now compile the project to have their effect affected
     */
    function compile();

    /**
     * @return mixed
     * this is just but a short cut for (loadAssets() && compile())
     */
    function fullCompilation();

    /**
     * @return mixed
     * this is simply a means of getting the package description from
     * composer.json
     */
   function packageInfo();

    /**
     * @return mixed
     * test if the package is installed
     */
   function testPackageIsInstalled();
}
