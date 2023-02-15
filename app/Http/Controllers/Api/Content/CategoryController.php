<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller

{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View All Category',
            'data' => $categories,
        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Category Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View Category',
            'data' => $category,
        ], 200);
    }

    public function store(Request $request)
    {
        if ($request->name_category == null) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Name Category is required'
            ], 400);
        } else {
            $category = Category::where('name_category', $request->name_category)->first();
            if ($category) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Name Category is already exist'
                ], 400);
            }
            $category = Category::create([
                'name_category' => $request->name_category,
                'slug_category' => Str::slug($request->name_category,)
            ]);
            return response()->json([
                'status' => 'Success',
                'message' => 'Success Create Category',
                'data' => $category,
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Category Not Found'
            ], 404);
        } else {
            if ($request->name_category == null) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Name Category is required'
                ], 400);
            } else {
                $category = Category::where('name_category', $request->name_category)->first();
                if ($category) {
                    return response()->json([
                        'status' => 'Failed',
                        'message' => 'Name Category is already exist'
                    ], 400);
                }
                $category = Category::find($id);
                $category->update([
                    'name_category' => $request->name_category,
                    'slug_category' => Str::slug($request->name_category,)
                ]);
                return response()->json([
                    'status' => 'Success',
                    'message' => 'Success Update Category',
                    'data' => $category,
                ], 200);
            }
        }
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Category Not Found'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Delete Category',
            'data' => $category,
        ], 200);
    }
}
