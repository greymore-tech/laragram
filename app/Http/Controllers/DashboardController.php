<?php

namespace App\Http\Controllers;

use MadelineProto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $get_contacts = MadelineProto::getClient()->contacts->getContacts();

        $contacts = $get_contacts['users'];

        $contacts = json_encode($contacts);

        return view('dashboard', compact('contacts'));
    }
}
