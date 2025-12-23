<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request and update user's last activity time.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update last activity time for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update last activity time if it's been more than 1 minute since last update
            // This prevents excessive database updates on every request
            if (!$user->last_activity_at || $user->last_activity_at->diffInMinutes(now()) >= 1) {
                $user->last_activity_at = now();
                $user->save();
            }
        }

        return $next($request);
    }
}