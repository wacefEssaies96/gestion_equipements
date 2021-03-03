<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        if(Auth::user()->role == 'ADMIN')
        {
            // return redirect()->route('admin.dashboard');
            return $next($request);
        }
        else
        {
            return redirect('/home');
        }
        // if(Auth::user()->role == 'ADMIN')
        // {
        //     return 'dashboard';
        // }
        // else if(Auth::user()->role == 'TECHNICIEN')
        // {
        //     return redirect('/technicien');
        // }
        // else
        // {
        //     return redirect('/home');
        // }
        
    }
}
