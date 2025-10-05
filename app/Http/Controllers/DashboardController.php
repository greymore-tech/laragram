<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Facades\MadelineProto;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard with a list of recent chats.
     */
    public function index()
    {
        $mp = MadelineProto::getClient();
        $dialogs = $mp->getFullDialogs();
        $get_dialogs = $mp->messages->getPeerDialogs(['peers' => $dialogs]);

        $messages = json_encode($get_dialogs['messages']);
        $chats = json_encode($get_dialogs['chats']);
        $users = json_encode($get_dialogs['users']);

        $self = MadelineProto::getSelf();
        $current_user_first_name = $self['first_name'];
        $current_user_id = $self['id'];

        return view('dashboard', compact('messages', 'chats', 'users', 'current_user_id', 'current_user_first_name'));
    }

    /**
     * Show the chat view for a specific user.
     */
    public function showUserMessages($other_user_id)
    {
        $mp = MadelineProto::getClient();
        $peer = (int) $other_user_id;
        $history = ['messages' => [], 'users' => []];

        try {
            $history = $mp->messages->getHistory([
                'peer' => $peer, 'limit' => 100, 'offset_id' => 0,
                'offset_date' => 0, 'add_offset' => 0, 'max_id' => 0, 'min_id' => 0
            ]);
        } catch (\Throwable $e) {
            Log::error("DashboardController: Error fetching history for user {$peer}: " . $e->getMessage());
        }

        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        $self = MadelineProto::getSelf();
        $current_user_id = $self['id'];
        $current_user_first_name = $self['first_name'];

        $other_user_info_array = [];
        try {
            $get_info = $mp->getInfo($peer);
            $other_user_info_array = $get_info['User'];
        } catch (\Throwable $e) {
            Log::warning("DashboardController: Could not get info for user {$peer}: " . $e->getMessage());
            $other_user_info_array = ['id' => $peer, 'first_name' => 'Inaccessible User', 'last_name' => ''];
        }
        $other_user_info = json_encode($other_user_info_array);

        return view('users', compact('messages', 'users', 'current_user_id', 'other_user_id', 'other_user_info', 'current_user_first_name'));
    }

    /**
     * Show the chat view for a specific group.
     */
    public function showGroupMessages($group_id)
    {
        $mp = MadelineProto::getClient();
        $peer_string = "chat#{$group_id}";
        $history = ['messages' => [], 'users' => []];

        try {
            $history = $mp->messages->getHistory(['peer' => $peer_string, 'limit' => 100]);
        } catch (\Throwable $e) {
            Log::error("DashboardController: Error fetching history for group {$peer_string}: " . $e->getMessage());
        }

        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        $self = MadelineProto::getSelf();
        $current_user_id = $self['id'];
        $current_user_first_name = $self['first_name'];

        $group_info_array = [];
        try {
            $get_info = $mp->getInfo($peer_string);
            $group_info_array = $get_info['Chat'];
        } catch (\Throwable $e) {
            Log::warning("DashboardController: Could not get info for group {$group_id}: " . $e->getMessage());
            $group_info_array = ['id' => $group_id, 'title' => 'Inaccessible Group'];
        }
        $group_info = json_encode($group_info_array);

        return view('groups', compact('messages', 'users', 'group_info', 'current_user_id', 'current_user_first_name'));
    }

    /**
     * Show the chat view for a specific channel.
     */
    public function showChannelMessages($channel_id)
    {
        $mp = MadelineProto::getClient();
        $peer = (int) $channel_id;
        $history = ['messages' => []];

        try {
            $history = $mp->messages->getHistory(['peer' => $peer, 'limit' => 100]);
        } catch (\Throwable $e) {
            Log::error("DashboardController: Error fetching history for channel {$peer}: " . $e->getMessage());
        }

        $messages = json_encode($history['messages']);

        $self = MadelineProto::getSelf();
        $current_user_id = $self['id'];
        $current_user_first_name = $self['first_name'];

        $channel_info_array = [];
        try {
            $get_info = $mp->getInfo($peer);
            $channel_info_array = $get_info['Chat'];
        } catch (\Throwable $e) {
            Log::warning("DashboardController: Could not get info for channel {$channel_id}: " . $e->getMessage());
            $channel_info_array = ['id' => $channel_id, 'title' => 'Inaccessible Channel'];
        }
        $channel_info = json_encode($channel_info_array);

        return view('channels', compact('messages', 'channel_info', 'current_user_id', 'current_user_first_name'));
    }

    /**
     * Show the view for creating a new group.
     */
    public function group() {
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();
        $users = json_encode($get_contacts['users']);
        $self = MadelineProto::getSelf();
        $current_user_first_name = $self['first_name'];
        return view('create_group', compact('users', 'current_user_first_name'));
    }

    /**
     * Handle the creation of a new group.
     */
    public function createGroup(Request $request)
    {
        MadelineProto::getClient()->messages->createChat(['users' => $request['user_id'], 'title' => $request['title']]);
        return redirect()->intended('/dashboard');
    }

    /**
     * Pin a group dialog.
     */
    public function groupPin($group_id) {
        MadelineProto::getClient()->messages->toggleDialogPin(['pinned' => true, 'peer' => "chat#$group_id"]);
        return redirect()->back();
    }

    /**
     * Unpin a group dialog.
     */
    public function groupUnpin($group_id) {
        MadelineProto::getClient()->messages->toggleDialogPin(['pinned' => false, 'peer' => "chat#$group_id"]);
        return redirect()->back();
    }

    /**
     * Show the view for creating a new channel.
     */
    public function channel()
    {
        $self = MadelineProto::getSelf();
        $current_user_first_name = $self['first_name'];
        return view('create_channel', compact('current_user_first_name'));
    }

    /**
     * Handle the creation of a new channel.
     */
    public function createChannel(Request $request)
    {
        MadelineProto::getClient()->channels->createChannel(['broadcast' => true, 'title' => $request->title, 'about' => $request->about]);
        return redirect()->intended('/dashboard');
    }

    /**
     * Show the user's contact list.
     */
    public function showContacts()
    {
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();
        $users = json_encode($get_contacts['users']);
        $self = MadelineProto::getSelf();
        $current_user_first_name = $self['first_name'];
        return view('contacts', compact('users', 'current_user_first_name'));
    }
}
