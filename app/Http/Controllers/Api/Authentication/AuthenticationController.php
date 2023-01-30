<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'full_name' => 'required|string|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already exists'
            ], 409);
        } else {
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'message' => 'Successfully created user!',
                'data' => $user,
            ], 201);
        }

        // if (User::where('email', $request->email)->exists()) {
        //     return response()->json([
        //         'message' => 'Email already exists'
        //     ], 409);
        // } else {
        //     $user = User::create([
        //         'email' => $request->email,
        //         'full_name' => $request->full_name,
        //         'password' => bcrypt($request->password)
        //     ]);

        //     $user->save();
        //     return response()->json([
        //         'message' => 'Successfully created user!',
        //         'data' => $user,
        //     ], 201);
        // }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Wrong email or unregistered'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password wrong'
            ], 401);
        }

        $accessToken = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'data' => $user,
            'access_token' => $accessToken
        ], 200);

        // Auth::login($user);
        // return response()->json([
        //     'message' => 'Successfully logged in',
        //     'data' => $user
        // ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
