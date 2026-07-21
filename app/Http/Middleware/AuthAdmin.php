<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Log pour déboguer (optionnel)
        Log::info('AuthAdmin middleware check', [
            'path' => $request->path(),
            'auth_check' => auth('admin')->check(),
        ]);

        if (!auth('admin')->check()) {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}