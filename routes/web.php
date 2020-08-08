<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'AuthController@login')->name('auth.login');
Route::post('login', 'AuthController@loginCheck')->name('auth.login.check');
Route::post('login/check', 'AuthController@loginCodeCheck')->name('auth.login.code.check');
Route::get('logout', 'AuthController@logout')->name('auth.logout');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');
