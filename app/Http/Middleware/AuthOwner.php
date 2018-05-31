<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Setting;

class AuthOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if( Auth::check() ) {     
            
            $auth = Auth::user();

            if( $auth->group != 'owner') {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
