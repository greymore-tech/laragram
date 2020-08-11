<?php

namespace App\Http\Controllers;

// use Messages;
use MadelineProto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();

        $contacts = json_encode($get_contacts['users']);

        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        return view('dashboard', compact('contacts', 'current_user_first_name'));
    }

    public function messagesHistory($other_user_id)
    {
        $current_time_in_seconds = time();

        $current_user = MadelineProto::fullGetSelf();
        $current_user_id = $current_user->id;
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        $history = MadelineProto::getClient()->messages->getHistory(['peer' => $other_user_id, 'offset_id' => 200, 'offset_date' => $current_time_in_seconds, 'add_offset' => 0, 'limit' => 1000, 'max_id' => 200, 'min_id' => 1]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        return view('messages', compact('messages', 'users', 'current_user_id', 'other_user_id', 'current_user_first_name'));
    }

    public function sendMessage(Request $request, $other_user_id)
    {
        $message_sent = MadelineProto::getClient()->messages->sendMessage(['peer' => $other_user_id, 'message' => $request->message]);

        return redirect()->back();
    }
}
