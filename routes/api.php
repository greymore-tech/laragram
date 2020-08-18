<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//  Users API Route
Route::get('user/messages/{user_id}', 'ChatController@fetchUsersMessages');
Route::get('user/{user_id}', 'ChatController@fetchUsers');

//  Groups API Route
Route::get('group/messages/{group_id}', 'ChatController@fetchGroupsMessages');
Route::get('group/{group_id}', 'ChatController@fetchGroupsUsers');

//  Channels API Route
Route::get('channel/messages/{channel_id}', 'ChatController@fetchChannelsMessages');
