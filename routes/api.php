<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('profile-photo/{peerType}/{peerId}', [ChatController::class, 'getProfilePhoto']);

Route::get('dialogs', [ChatController::class, 'getDialogs']);

// Users API Route
Route::get('user/chat-data/{user_id}', [ChatController::class, 'getUserChatData']);
Route::post('dashboard/message/user/{other_user_id}/send', [ChatController::class, 'sendUserMessage']);

// Groups API Route
Route::get('group/messages/{group_id}', [ChatController::class, 'fetchGroupsMessages']);
Route::get('group/{group_id}', [ChatController::class, 'fetchGroupsUsers']);
Route::post('dashboard/message/group/{group_id}/send', [ChatController::class, 'sendGroupMessage']);

// Channels API Route
Route::get('channel/messages/{channel_id}', [ChatController::class, 'fetchChannelsMessages']);
Route::post('dashboard/message/channel/{channel_id}/send', [ChatController::class, 'sendChannelMessage']);

Route::get('media/download', [ChatController::class, 'downloadMedia']);
