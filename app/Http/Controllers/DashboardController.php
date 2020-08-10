<?php

namespace App\Http\Controllers;

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

    public function messagesHistory($user_id)
    {
        $currentTimeinSeconds = time();

        $current_user = MadelineProto::fullGetSelf();
        $current_user = $current_user->id;
        $current_user_first_name = MadelineProto::fullGetSelf();
        $current_user_first_name = $current_user_first_name->first_name;

        $history = MadelineProto::getClient()->messages->getHistory(['peer' => $user_id, 'offset_id' => 200, 'offset_date' => $currentTimeinSeconds, 'add_offset' => 0, 'limit' => 1000, 'max_id' => 200, 'min_id' => 1, ]);
        $messages = json_encode($history['messages']);
        $users = json_encode($history['users']);

        return view('messages', compact('messages', 'users', 'current_user', 'current_user_first_name'));
    }
}
