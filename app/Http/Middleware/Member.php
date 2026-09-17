<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Member
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->level == 0) {
            return $next($request);
        }

        if (Auth::check()) {
            // Auth::logout();
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            abort(403, 'You do not have access to this page.');
        }

        return redirect(route('frontend.login'))->with('error', 'Please log in before taking this action!');
    }
}
