<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|confirmed|min:8'
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password)
        ]);

        $accessToken = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => 'Registrasi Success',
            'data' => $user,
            'meta' => [
                'token' => $accessToken
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $accessToken = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'data' => $user,
            'meta' => [
                'token' => $accessToken
            ]
        ], 201);
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

        $user->sendPasswordResetNotification($user->createToken($request->email)->plainTextToken);

        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Success'
        ], 200);
    }
}
