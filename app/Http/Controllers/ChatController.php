<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use MadelineProto;
use Illuminate\Http\Request;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    public function fetchUsersMessages($other_user_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timestamp;

        //  get last sent messages based on user id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["user#$other_user_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on user id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "user#$other_user_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = $history['messages'];

        return ChatResource::collection($messages);
    }

    public function fetchUsers($other_user_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timestamp;

        //  get last sent messages based on user id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["user#$other_user_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on user id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "user#$other_user_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $users = $history['users'];

        return ChatResource::collection($users);
    }

    public function fetchGroupsMessages($group_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get last sent messages based on group id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["chat#$group_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "chat#$group_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = $history['messages'];

        return ChatResource::collection($messages);
    }

    public function fetchGroupsUsers($group_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get last sent messages based on group id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["chat#$group_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "chat#$group_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $users = $history['users'];

        return ChatResource::collection($users);
    }

    public function fetchChannelsMessages($channel_id)
    {
        //  get current time
        $current_time_in_seconds = Carbon::now()->timezone('Asia/Calcutta')->timestamp;

        //  get last sent messages based on channel id
        $get_dialogs = MadelineProto::getClient()->messages->getPeerDialogs(['peers' => ["channel#$channel_id"]]);
        $top_message = $get_dialogs['dialogs'][0]['top_message'];
        $message_offset = $top_message+1;

        //  get all the messages based on channel id
        $history = MadelineProto::getClient()->messages->getHistory(['peer' => "channel#$channel_id", 'offset_id' => $message_offset, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 10000, 'max_id' => $message_offset, 'min_id' => 1]);
        $messages = $history['messages'];

        return ChatResource::collection($messages);
    }
}
