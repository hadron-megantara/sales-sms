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

Auth::routes();

Route::get('login', 'Web\Auth\LoginController@login')->name('login');
Route::post('login', 'Web\Auth\LoginController@loginProcess');

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'Web\HomeController@index')->name('home');
    Route::get('/config', 'Web\ConfigController@index');

    Route::get('/config/user', 'Web\ConfigController@user');
});
