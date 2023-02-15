<?php

namespace App\Http\Controllers\Api\Setting;

use App\Models\Carousel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CarouselResource;

class CarouselController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
    }

    public function index()
    {
        if (Carousel::all()->count() == 0) {
            return response()->json([
                'status' => 'Success',
                'message' => 'Carousel Image Not Found',
            ], 200);
        }
        $carousel = Carousel::all();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Get Carousel',
            'data' => CarouselResource::collection($carousel)
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

    public function upload(Request $request)
    {
        $request->validate([
            'carousel' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $carousel = $request->file('carousel');
        $name_file = $this->generateRandomString();
        $extension = $carousel->getClientOriginalExtension();
        $name = $name_file . '.' . $extension;
        $carousel = Storage::putFileAs('carousel', $request->file('carousel'), $name);

        $carousel = Carousel::create([
            'carousel' => $carousel
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Success Upload Carousel',
            'data' => new CarouselResource($carousel)
        ], 200);
    }

    public function destroy($id)
    {
        $carousel = Carousel::find($id);
        if (!$carousel) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Carousel Image Not Found',
            ], 404);
        } else {
            Storage::delete($carousel->carousel);
            $carousel->delete();
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Delete Carousel',
            ], 200);
        }
    }
}
