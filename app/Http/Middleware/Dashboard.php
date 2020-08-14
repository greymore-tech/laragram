<?php

namespace App\Http\Middleware;

use Closure;
use MadelineProto;

class Dashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $get_user = MadelineProto::fullGetSelf();

        if ($get_user != false) {
            return $next($request);
        }

        return redirect()->intended('/login');
    }
}
