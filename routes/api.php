<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('prefix' => 'v1'), function(){
    Route::post('/login', 'Api\LoginController@login');

    Route::middleware(['auth:api'])->group(function () {
        Route::group(array('prefix' => 'config'), function(){
            Route::get('/menu', 'Api\ConfigController@getMenu');
            Route::get('/permission', 'Api\ConfigController@permission');
            Route::get('/roles', 'Api\ConfigController@roles');
            Route::get('/users', 'Api\ConfigController@users');
        });

        Route::group(array('prefix' => 'master'), function(){
            Route::get('/area', 'Api\MasterController@getArea');
            Route::get('/company', 'Api\MasterController@getCompany');
            Route::get('/district', 'Api\MasterController@getDistrict');
            Route::get('/price-profile', 'Api\MasterController@getPriceProfile');
            Route::get('/warehouse', 'Api\MasterController@getWarehouse');
        });

        Route::group(array('prefix' => 'user'), function(){
            Route::get('profile', 'Api\UserController@getProfile');
        });

        Route::resource('/outlet', 'Api\OutletController');
        Route::post('/outlet-photo', 'Api\OutletController@upload');

        Route::resource('/sim-rs', 'Api\SimRsController');

    });
});
