<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();

Route::get('login', 'Web\Auth\LoginController@login')->name('login');
Route::post('login', 'Web\Auth\LoginController@loginProcess');

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'Web\HomeController@index')->name('home');

    Route::group(array('prefix' => 'config'), function(){
        Route::get('/', 'Web\ConfigController@index');
        Route::get('user', 'Web\ConfigController@user');
        Route::get('roles', 'Web\ConfigController@roles');
        Route::get('permissions', 'Web\ConfigController@permissions');
        Route::get('menu', 'Web\ConfigController@menu');
    });

    Route::group(array('prefix' => 'profile'), function(){
        Route::get('index', 'Web\UserController@index');
        Route::get('edit', 'Web\UserController@edit');
        Route::post('update', 'Web\UserController@update');
    });
});
