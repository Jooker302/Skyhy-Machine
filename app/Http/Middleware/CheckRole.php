<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $role_id = Auth::user()->role_id;

            if ($role_id == 1 && $request->is('show-calendar')) {
                return redirect('/home')->with('error', "You can't access the Calendar page.");
            } elseif ($role_id == 2 && $request->is('home')) {
                return redirect('show-calendar')->with('error', "You can't access the Admin page.");
            }

            return $next($request);
        }

        return redirect('/');
    }
}
