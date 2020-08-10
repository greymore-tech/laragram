<?php

namespace App\Http\Controllers;

use MadelineProto;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginCheck(Request $request)
    {
        $phone = $request->phone;
        MadelineProto::phoneLogin($phone);

        return view('login_code_check');
    }

    public function loginCodeCheck(Request $request)
    {
        $secret_code = $request->secret_code;
        MadelineProto::completePhoneLogin($secret_code);

        return redirect()->intended('dashboard');
    }

    public function logout()
    {
        MadelineProto::logout();

        return redirect()->intended('/');
    }
}
