<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Success',
            'data' => $request->user()
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'username' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ]);

        $request->user()->update([
            'email' => $request->email,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return response()->json([
            'message' => 'Update Profile Success',
            'data' => $request->user()
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->user()->delete();

        return response()->json([
            'message' => 'Delete Success',
            'data' => null
        ], 200);
    }
}
