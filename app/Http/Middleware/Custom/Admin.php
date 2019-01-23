<?php

namespace App\Http\Middleware\Custom;

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
        if( ! auth()->user()->isAdmin() ) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
