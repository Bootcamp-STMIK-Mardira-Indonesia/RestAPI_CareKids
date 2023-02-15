<?php

namespace App\Http\Controllers\Api\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingAppController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
    }
    public function index()
    {
        if (Setting::count() > 0) {
            $setting = Setting::first();
            return response()->json([
                'status' => 'success',
                'data' => $setting
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 404);
        }
    }

    function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function store(Request $request)
    {
        $setting = Setting::create([
            'header' => $request->header,
            'footer' => $request->footer,
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'waktu' => $request->waktu,
            'tlp' => $request->tlp,
            'email' => $request->email,
        ]);

        $logo = null;
        if ($request->file('logo')) {
            $file_name = $this->generateRandomString();
            $extension = $request->file('logo')->getClientOriginalExtension();
            $logo = $file_name . '.' . $extension;

            $logo = Storage::putFileAs('logo', $request->file('logo'), $logo);
        }

        $setting = Setting::create([
            'header' => $request->header,
            'footer' => $request->footer,
            'logo' => $logo,
            'waktu' => $request->waktu,
            'tlp' => $request->tlp,
            'email' => $request->email,
        ]);

        if ($setting) {
            return response()->json([
                'status' => true,
                'message' => 'Setting Saved',
                'data' => $setting
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Setting Failed to Save',
            ], 409);
        }
    }


    public function update(Request $request, $id)
    {
        $setting = Setting::find($id);
        $setting->update([
            'header' => $request->header,
            'footer' => $request->footer,
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'waktu' => $request->waktu,
            'tlp' => $request->tlp,
            'email' => $request->email,
        ]);

        $logo = null;
        if ($request->file('logo')) {
            $file_name = $this->generateRandomString();
            $extension = $request->file('logo')->getClientOriginalExtension();
            $logo = $file_name . '.' . $extension;

            $logo = Storage::putFileAs('logo', $request->file('logo'), $logo);
        }

        $setting = Setting::find($id);
        $setting->update([
            'header' => $request->header,
            'footer' => $request->footer,
            'logo' => $logo,
            'waktu' => $request->waktu,
            'tlp' => $request->tlp,
            'email' => $request->email,
        ]);

        if ($setting) {
            return response()->json([
                'status' => true,
                'message' => 'Setting Updated',
                'data' => $setting
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Setting Failed to Update',
            ], 409);
        }
    }
}
