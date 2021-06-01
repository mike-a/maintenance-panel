<?php
/**
 * Created by PhpStorm
 * User: jjoek
 * Date: 6/1/21
 * Time: 1:57 pm
 */
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => "Vivinet\\EngineersConsole\\Http\\Controllers", 'as' => 'engineers-console.', 'prefix' => 'engineers-console', 'middleware' => ['web']], function () {

    // analysis
    Route::match(['post', 'get'],'/setup', 'SetupController@setup')->name('setup');
    Route::post('/package/setup', 'SetupController@packageSetup')->name('package-setup');
});
