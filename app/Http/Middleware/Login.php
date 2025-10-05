<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Facades\MadelineProto;

class Login
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $get_user = MadelineProto::getSelf();
            if ($get_user) {
                return redirect('/dashboard');
            }
        } catch (\Throwable $e) {
            // Not logged in, so proceed.
        }

        return $next($request);
    }
}
