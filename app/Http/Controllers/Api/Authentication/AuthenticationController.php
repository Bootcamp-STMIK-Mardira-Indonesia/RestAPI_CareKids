<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'full_name' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Email already exists'
            ], 409);
        } else {
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Successfully created user!',
                'data' => $user,
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Wrong email or unregistered'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Password wrong'
            ], 401);
        }

        $accessToken = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Successfully logged in',
            'data' => $user,
            'access_token' => $accessToken
        ], 200);
    }

    function generateRandomString($length = 50)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function index()
    {
        $user = User::all();
        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'Successfully get  all user!',
                'data' => $user,
            ], 200);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'Success',
                'message' => 'Successfully get user!',
                'data' => $user,
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Successfully logged out'
        ], 200);
    }
}
