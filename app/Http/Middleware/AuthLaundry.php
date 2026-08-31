<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthLaundry
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('laundry')->check()) {
            return redirect()->route('laundry.login');
        }
        return $next($request);
    }
}