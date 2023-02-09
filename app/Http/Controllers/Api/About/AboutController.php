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
                'message' => 'About Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'About View All Comments',
            'pesan' => $abouts,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'misi' => 'required|text',
            'visi' => 'required|text',

        ]);

        $abouts = About::create([
            'misi' => $request->misi,
            'visi' => $request->visi,
        ]);
        return response()->json([
            'message' => 'Success Create Comment',
            'data' => $abouts,
        ], 201);
    }

    public function destroy($id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json([
                'message' => 'About Not Found'
            ], 404);
        }
        $about->delete();
        return response()->json([
            'message' => 'Success About',
            'data' => $about,
        ], 200);
    }
}
