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

//  User Authentication Routes
Route::get('login', 'AuthController@login')->name('auth.login');
Route::post('login', 'AuthController@loginCheck')->name('auth.login.check');
Route::post('login/check', 'AuthController@loginCodeCheck')->name('auth.login.code.check');
Route::get('logout', 'AuthController@logout')->name('auth.logout');

//  User Dashboard Routes
Route::get('dashboard', 'DashboardController@index')->name('dashboard');
//  User Messages Routes
Route::get('dashboard/messages/user/{other_user_id}', 'DashboardController@showUserMessages')->name('dashboard.user.messages');
Route::post('dashboard/message/user/{other_user_id}/send', 'DashboardController@sendUserMessage')->name('dashboard.user.message.send');
//  Group Messages Routes
Route::get('dashboard/messages/group/{group_id}', 'DashboardController@showGroupMessages')->name('dashboard.group.messages');
//  Channel Messages Routes
Route::get('dashboard/messages/channel/{channel_id}', 'DashboardController@showChannelMessages')->name('dashboard.channel.messages');
Route::post('dashboard/message/channel/{channel_id}/send', 'DashboardController@sendChannelMessage')->name('dashboard.channel.message.send');
//  Create Channel
Route::post('dashboard/channel/create', 'DashboardController@createChannel')->name('dashboard.channel.create');
