<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->level == 1) {
            return $next($request);
        }

        if (Auth::check()) {
            abort(403, 'You do not have access to this page.');
            // Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
        }
        return redirect(route('admin.login'))->with('error', 'Please log in before taking this action!');
    }
}
