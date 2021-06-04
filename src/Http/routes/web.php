<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 1:57 pm
 */
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => "Vivinet\\MaintenancePanel\\Http\\Controllers", 'as' => 'maintenance-panel.', 'prefix' => 'maintenance-panel', 'middleware' => ['web']], function () {

    // analysis
    Route::match(['post', 'get'],'/', 'SetupController@setup')->name('setup');
    Route::post('/package/setup', 'SetupController@packageSetup')->name('package-setup');
});
