<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// The welcome page and authentication routes
Route::get('/', function () {
    // Laravel 11 has a beautiful default welcome page. We'll add the login link.
    return view('welcome');
})->middleware('login');

Route::get('login', [AuthController::class, 'login'])->name('auth.login')->middleware('login');
Route::post('login', [AuthController::class, 'loginCheck'])->name('auth.login.check')->middleware('login');
Route::post('login/check', [AuthController::class, 'loginCodeCheck'])->name('auth.login.code.check')->middleware('login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('dashboard');

// All dashboard routes are grouped and protected by the dashboard middleware
Route::middleware('dashboard')->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Messages Routes
    Route::get('messages/user/{other_user_id}', [DashboardController::class, 'showUserMessages'])->name('dashboard.user.messages');
    Route::get('messages/group/{group_id}', [DashboardController::class, 'showGroupMessages'])->name('dashboard.group.messages');
    Route::get('messages/channel/{channel_id}', [DashboardController::class, 'showChannelMessages'])->name('dashboard.channel.messages');

    // Create Channel Routes
    Route::get('channel', [DashboardController::class, 'channel'])->name('dashboard.channel');
    Route::post('channel/create', [DashboardController::class, 'createChannel'])->name('dashboard.channel.create');

    // Create Group Routes
    Route::get('group', [DashboardController::class, 'group'])->name('dashboard.group');
    Route::post('group/create', [DashboardController::class, 'createGroup'])->name('dashboard.group.create');
    Route::get('group/pin/{group_id}', [DashboardController::class, 'groupPin'])->name('dashboard.group.pin');
    Route::get('group/unpin/{group_id}', [DashboardController::class, 'groupUnpin'])->name('dashboard.group.unpin');

    // Contacts Route
    Route::get('contacts', [DashboardController::class, 'showContacts'])->name('dashboard.contacts');
});
