<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\Image;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('show');
    }

    public function show($article_id)
    {
        $images = Image::where('article_id', $article_id)->get();
        if (!$images || $images->count() == 0) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Image Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View All Images This Article',
            'data' => ImageResource::collection($images),
        ], 200);
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

    public function upload(Request $request)
    {
        $request->validate([
            'name_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (!Article::find($request->article_id)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Article Not Found'
            ], 404);
        }

        $image = null;
        if ($request->hasFile('image')) {
            $files = $request->file('image');

            foreach ($files as $file) {
                $image_name = $this->generateRandomString();
                $extension = $file->getClientOriginalExtension();
                $image = $image_name . '.' . $extension;
                $image = Storage::putFileAs('image', $file, $image);

                $post = Image::create([
                    'name_image' => $image,
                    'article_id' => $request->article_id
                ]);
            }
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Upload Image',
                'data' => $post
            ], 200);
        }
    }

    public function delete($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Image Not Found'
            ], 404);
        }
        $image->delete();
        Storage::delete($image->name_image);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Delete Image',
            'data' => $image,
        ], 200);
    }
}
