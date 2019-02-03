<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Auth\AuthenticationException;

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
        if( !$request->user()->isAdmin() ) {
            abort(403, 'This action is not authorized!');
        }

        return $next($request);
    }
}
