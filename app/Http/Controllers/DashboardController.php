<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use MadelineProto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('dashboard');
    }

    public function index()
    {
        //  get all recent messages with user, group and channel details
        $dialogs = MadelineProto::getClient()->getFullDialogs();
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => $dialogs]);
        $messages = json_encode($get_dialogs['messages']);
        $chats = json_encode($get_dialogs['chats']);
        $users = json_encode($get_dialogs['users']);

        //  get the first name of the current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        //  get the id of the current logged in user
        $current_user_id = MadelineProto::fullGetSelf();
        $current_user_id = $current_user_id->id;

        return view('dashboard', compact('messages', 'chats', 'users', 'current_user_id', 'current_user_first_name'));
    }

    public function showUserMessages($other_user_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timestamp;

        //  get last sent messages based on user id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["user#$other_user_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on user id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "user#$other_user_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        // get user info based on user id
        $get_info = MadelineProto::getClient()->getInfo($other_user_id);
        $other_user_info = json_encode($get_info['User']);

        return view('users', compact('messages', 'users', 'current_user_id', 'other_user_id', 'other_user_info', 'current_user_first_name'));
    }

    public function group() {
        //  get all the user contacts registered on telegram of current logged in user
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();
        $users = json_encode($get_contacts['users']);

        //  get the first name of the current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        return view('create_group', compact('users', 'current_user_first_name'));
    }

    public function groupPin($group_id) {
        MadelineProto::getClient()->messages->toggleDialogPin(['pinned' => 1, 'peer' => "chat#$group_id", ]);

        return redirect()->back();
    }

    public function groupUnpin($group_id) {
        MadelineProto::getClient()->messages->toggleDialogPin(['pinned' => 0, 'peer' => "chat#$group_id", ]);

        return redirect()->back();
    }

    public function createGroup(Request $request)
    {
        //  create a new group with group title and group members
        MadelineProto::getClient()->messages->createChat(['users' => $request['user_id'], 'title' => $request['title'], ]);

        return redirect()->intended('/dashboard');
    }

    public function showGroupMessages($group_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get last sent messages based on group id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["chat#$group_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "chat#$group_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        // get group info based on group id
        $get_info = MadelineProto::getClient()->getInfo("chat#$group_id");
        $group_info = json_encode($get_info['Chat']);

        return view('groups', compact('messages', 'users', 'group_info', 'current_user_id', 'current_user_first_name'));
    }

    public function channel()
    {
        //  get the first name of the current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        return view('create_channel', compact('current_user_first_name'));
    }

    public function createChannel(Request $request)
    {
        //  create a new channel with channel title and about
        MadelineProto::getClient()->channels->createChannel(['broadcast' => true, 'title' => $request->title, 'about' => $request->about]);

        return redirect()->intended('/dashboard');
    }

    public function showChannelMessages($channel_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get last sent messages based on channel id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["channel#$channel_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "channel#$channel_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = json_encode($history['messages']);

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        // get channel info based on channel id
        $get_info = MadelineProto::getClient()->getInfo("channel#$channel_id");
        $channel_info = json_encode($get_info['Chat']);

        return view('channels', compact('messages', 'channel_info', 'current_user_id', 'current_user_first_name'));
    }

    public function showContacts()
    {
        //  get all the user contacts registered on telegram of current logged in user
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();
        $users = json_encode($get_contacts['users']);

        //  get the first name of the current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        return view('contacts', compact('users', 'current_user_first_name'));
    }
}
