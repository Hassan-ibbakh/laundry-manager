<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthLaundry
{
    public function handle(Request $request, Closure $next)
    {
        // Log pour déboguer (optionnel)
        Log::info('AuthLaundry middleware check', [
            'path' => $request->path(),
            'auth_check' => auth('laundry')->check(),
        ]);

        if (!auth('laundry')->check()) {
            return redirect()->route('laundry.login');
        }
        return $next($request);
    }
}