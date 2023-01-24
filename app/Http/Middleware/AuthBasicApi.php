<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthBasicApi
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
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $user = User::where('email', $_SERVER['PHP_AUTH_USER'])->first();
            if ($user && Hash::check($_SERVER['PHP_AUTH_PW'], $user->password)) {
                Auth::login($user);
                return $next($request);
            }
        }
        return response()->json(['message' => 'Please Login or Registed'], 401, [
            'WWW-Authenticate' => 'Basic'
        ]);
    }
}
