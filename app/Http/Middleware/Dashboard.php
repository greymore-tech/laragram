<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Facades\MadelineProto;

class Dashboard
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $get_user = MadelineProto::getSelf();
            if ($get_user) {
                return $next($request);
            }
        } catch (\Throwable $e) {
            // If any error occurs (like not being logged in), redirect.
        }

        return redirect('/login');
    }
}
