<?php

namespace App\Http\Controllers\Api\About;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::all();
        if (!$abouts || $abouts->count() == 0) {
            return response()->json([
                'message' => 'About_Us Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'About_Us View All',
            'data' => $abouts,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'misi' => 'required|string',
            'visi' => 'required|string',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $abouts = About::create([
            'misi' => $request->misi,
            'visi' => $request->visi,
            'logo' => $request->logo,
        ]);
        return response()->json([
            'message' => 'Success Create About_Us..',
            'data' => $abouts,
        ], 201);
    }

    public function destroy($id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json([
                'message' => 'About_Us Not Found'
            ], 404);
        }
        $about->delete();
        return response()->json([
            'message' => 'Success Delete About_Us',
            'data' => $about,
        ], 200);
    }
}
