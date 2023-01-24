<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|confirmed|min:6'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already exists'
            ], 409);
        } elseif (User::where('username', $request->username)->exists()) {
            return response()->json([
                'message' => 'Username already exists'
            ], 409);
        } else {
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => bcrypt($request->password)
            ]);

            $user->save();
            return response()->json([
                'message' => 'Successfully created user!',
                'data' => $user,
            ], 201);
        }
    }

    public function login(Request $request)
    {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $user = User::where('email', $_SERVER['PHP_AUTH_USER'])->first();
            //email not wrong or not registered
            if (!$user) {
                return response()->json([
                    'message' => 'Wrong email or unregistered'
                ], 401);
            }

            //password wrong
            if (!Hash::check($_SERVER['PHP_AUTH_PW'], $user->password)) {
                return response()->json([
                    'message' => 'Password wrong'
                ], 401);
            }
            Auth::login($user);
            return response()->json([
                'message' => 'Successfully logged in',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response([
                'message' => ['We can\'t find a user with that e-mail address.']
            ], 404);
        }
        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
