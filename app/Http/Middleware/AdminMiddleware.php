<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is admin
        if (!Auth::check() || !session('is_admin') || Auth::user()->email !== 'safa@admin.com') {
            return redirect()->route('admin.login')
                ->withErrors(['access' => 'You must be logged in as admin to access this area.']);
        }

        return $next($request);
    }
}
