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

        //  get all the user contacts registered on telegram of current logged in user
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();
        $users = json_encode($get_contacts['users']);

        //  get all the channels and groups joined by the current logged in user
        $get_chats = MadelineProto::getClient()->messages->getAllChats();

        $groups = array();
        $channel = array();

        for ($i=0; $i<count($get_chats['chats']); $i++) {
            if ($get_chats['chats'][$i]['_'] == 'chat') {
                array_push($groups, $get_chats['chats'][$i]);
            }
            else {
                array_push($channel, $get_chats['chats'][$i]);
            }
        }

        $groups = json_encode($groups);
        $channels = json_encode($channel);

        //  store the first name of the current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        return view('dashboard', compact('users', 'groups', 'channels', 'current_user_first_name'));
    }

    public function showUserMessages($other_user_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timestamp;

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        //  get all the messages based on user id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => $other_user_id, 'offset_id' => 200, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 1000, 'max_id' => 200, 'min_id' => 1]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        // get user info based on user id
        $get_info = MadelineProto::getClient()->getInfo($other_user_id);
        $other_user_info = json_encode($get_info['User']);

        return view('users', compact('messages', 'users', 'current_user_id', 'other_user_id', 'other_user_info', 'current_user_first_name'));
    }

    public function sendUserMessage(Request $request, $other_user_id)
    {
        //  send the message to user based on user id
        MadelineProto::getClient()->messages->sendMessage(['peer' => $other_user_id, 'message' => $request->message]);

        return redirect()->back();
    }

    public function showGroupMessages($group_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "chat#$group_id", 'offset_id' => 40998, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 500, 'max_id' => 40998, 'min_id' => 1]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        // get group info based on group id
        $get_info = MadelineProto::getClient()->getInfo("chat#$group_id");
        $group_info = json_encode($get_info['Chat']);

        return view('groups', compact('messages', 'users', "group_info", 'current_user_id', 'current_user_first_name'));
    }

    public function sendGroupMessage(Request $request, $group_id)
    {
        //  send the message to group based on group id
        MadelineProto::getClient()->messages->sendMessage(['peer' => "chat#$group_id", 'message' => $request->message]);

        return redirect()->back();
    }

    public function createChannel(Request $request)
    {
        //  create a new channel with channel title and about
        MadelineProto::getClient()->channels->createChannel(['broadcast' => true, 'title' => $request->title, 'about' => $request->about]);

        return redirect()->back();
    }

    public function showChannelMessages($channel_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get id of current logged in user
        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        //  get first name of current logged in user
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "channel#$channel_id", 'offset_id' => 40998, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 500, 'max_id' => 40998, 'min_id' => 1]);
        $messages = json_encode($history['messages']);

        // get channel info based on channel id
        $get_info = MadelineProto::getClient()->getInfo("channel#$channel_id");
        $channel_info = json_encode($get_info['Chat']);

        return view('channels', compact('messages', 'channel_info', 'current_user_id', 'current_user_first_name'));
    }

    public function sendChannelMessage(Request $request, $channel_id)
    {
        //  send the message to channel based on channel id
        MadelineProto::getClient()->messages->sendMessage(['peer' => "channel#$channel_id", 'message' => $request->message]);

        return redirect()->back();
    }
}
