<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\Image;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'article_id' => 'required|integer'
        ]);
        if (!Article::find($request->article_id)) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        }

        $imageName = time() . '.' . $request->image->extension();
        Storage::putFileAs('images', $request->file('image'), $imageName);

        $image = Image::create([
            'article_id' => $request->article_id,
            'name_image' => $imageName,
        ]);

        return response()->json([
            'message' => 'Success Upload Image',
            'data' => $image,
        ], 201);
    }

    public function delete($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json([
                'message' => 'Image Not Found'
            ], 404);
        }
        $image->delete();
        return response()->json([
            'message' => 'Success Delete Image',
            'data' => $image,
        ], 200);
    }
}
