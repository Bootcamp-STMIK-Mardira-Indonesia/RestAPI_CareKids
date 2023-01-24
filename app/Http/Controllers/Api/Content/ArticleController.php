<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostsResource;

class ArticleController extends Controller
{
    public function index()
    {
        $posts = Article::all();
        if (!$posts) {
            return response()->json([
                'message' => 'Posts Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View All Posts',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
            ], 200);
        }
    }

    public function show($id)
    {
        $post = Article::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View Post',
                'data' => new PostsResource($post->LoadMissing(['user:id,username', 'category:id,name_category'])),
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
        ]);

        $request->merge([
            'user_id' => Auth::user()->id,
            'category_id' => Category::where('name_category', $request->category)->first()->id,
        ]);
        $post = Article::create($request->all());
        return response()->json([
            'message' => 'Success Create Post',
            'data' => new PostsResource($post->LoadMissing(['user:id,username', 'category:id,name_category'])),
        ], 200);
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            $posts->delete();
            return response()->json([
                'message' => 'Success Delete Post',
            ], 200);
        }
    }

    public function search(Request $request)
    {
        $posts = Article::where('title', 'like', '%' . $request->search . '%')->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success Search Post',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
            ], 200);
        }
    }

    public function searchByCategory($id)
    {
        $posts = Article::where('category_id', $id)->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success Search Post By Category',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
            ], 200);
        }
    }

    public function searchByUser($id)
    {
        // $posts = Article::where('user_id', $id)->get();
        // if (!$posts) {
        //     return response()->json([
        //         'message' => 'Post Not Found'
        //     ], 404);
        // } else {
        //     return response()->json([
        //         'message' => 'Success Search Post By User',
        //         'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
        //     ], 200);
        // }
    }

    public function searchByStatus($status)
    {
        // $posts = Article::where('status', $status)->get();
        // if (!$posts) {
        //     return response()->json([
        //         'message' => 'Post Not Found'
        //     ], 404);
        // } else {
        //     return response()->json([
        //         'message' => 'Success Search Post By Status',
        //         'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
        //     ], 200);
        // }
    }

    public function softDelete($id)
    {
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            $posts->delete();
            return response()->json([
                'message' => 'Success Soft Delete Post',
            ], 200);
        }
    }

    public function restore($id)
    {
        $posts = Article::onlyTrashed()->find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            $posts->restore();
            return response()->json([
                'message' => 'Success Restore Post',
            ], 200);
        }
    }

    public function forceDelete($id)
    {
        $posts = Article::onlyTrashed()->find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            $posts->forceDelete();
            return response()->json([
                'message' => 'Success Force Delete Post',
            ], 200);
        }
    }

    public function trash()
    {
        $posts = Article::onlyTrashed()->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Post Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View All Posts',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,username', 'category:id,name_category'])),
            ], 200);
        }
    }
}
