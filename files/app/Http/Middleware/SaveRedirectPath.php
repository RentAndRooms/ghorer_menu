<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SaveRedirectPath
{
    public function handle(Request $request, Closure $next)
    {
        // If there's a redirect path in the session storage
        if ($request->session()->has('redirect_after_auth')) {
            $redirectPath = $request->session()->get('redirect_after_auth');
            // Store it in the session
            session(['intended_url' => $redirectPath]);
        }

        return $next($request);
    }
}
