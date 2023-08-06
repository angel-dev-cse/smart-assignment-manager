<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Exclude the logout route from redirection
        if ($request->is('logout')) {
            return $next($request);
        }

        if ($user) {
            if (in_array($user->status, ['pending', 'declined'])) {
                // Exclude the verification page from redirection
                if ($request->is('verification')||$request->is('verification/reapply')) {
                    return $next($request);
                }

                // Redirect to the verification page or any other page you want to show
                return redirect()->route('verification.page');
            } elseif ($request->is('verification')) {
                // If the user is approved and trying to access /verification, redirect to dashboard
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}