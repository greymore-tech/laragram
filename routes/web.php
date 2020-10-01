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
})->middleware('login');

//  User Authentication Routes
Route::get('login', 'AuthController@login')->name('auth.login')->middleware('login');
Route::post('login', 'AuthController@loginCheck')->name('auth.login.check')->middleware('login');
Route::post('login/check', 'AuthController@loginCodeCheck')->name('auth.login.code.check')->middleware('login');
Route::get('logout', 'AuthController@logout')->name('auth.logout')->middleware('dashboard');

//  User Dashboard Routes
Route::get('dashboard', 'DashboardController@index')->name('dashboard');
//  User Messages Routes
Route::get('dashboard/messages/user/{other_user_id}', 'DashboardController@showUserMessages')->name('dashboard.user.messages');
//  Group Messages Routes
Route::get('dashboard/messages/group/{group_id}', 'DashboardController@showGroupMessages')->name('dashboard.group.messages');
//  Channel Messages Routes
Route::get('dashboard/messages/channel/{channel_id}', 'DashboardController@showChannelMessages')->name('dashboard.channel.messages');
//  Create Channel Routes
Route::get('dashboard/channel', 'DashboardController@channel')->name('dashboard.channel');
Route::post('dashboard/channel/create', 'DashboardController@createChannel')->name('dashboard.channel.create');
//  Create Group Routes
Route::get('dashboard/group', 'DashboardController@group')->name('dashboard.group');
Route::post('dashboard/group/create', 'DashboardController@createGroup')->name('dashboard.group.create');
//  Group Pin Route
Route::get('dashboard/group/pin/{group_id}', 'DashboardController@groupPin')->name('dashboard.group.pin');
Route::get('dashboard/group/unpin/{group_id}', 'DashboardController@groupUnpin')->name('dashboard.group.unpin');
//  Show Contacts Route
Route::get('dashboard/contacts', 'DashboardController@showContacts')->name('dashboard.contacts');
