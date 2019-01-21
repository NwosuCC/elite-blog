<?php

namespace App\Http\Middleware\Custom;

use App\User;

use Closure;

class Admin
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
        if( auth()->user()->role !== User::ADMIN_ROLE ) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
