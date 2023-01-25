<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostsResource;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('AuthBasicApi')->except('index', 'show');
    }
    public function index()
    {
        $post = Article::all();
        if (!$post || $post->count() == 0) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View All Articles',
                'data' => PostsResource::collection($post->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status', 'comments:id,comment'])),
            ], 200);
        }
    }

    public function show($id)
    {
        $post = Article::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View Article',
                'data' => new PostsResource($post->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status', 'comments:id,comment'])),
            ], 200);
        }
    }

    //random string
    function generateRandomString($length = 10)
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);
        $image = null;
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $image->storeAs('public/images', $image->getClientOriginalName());
        //     $image = $image->getClientOriginalName();
        // }
        $video = null;
        // if ($request->hasFile('video')) {
        //     $video = $request->file('video');
        //     $video->storeAs('public/videos', $video->getClientOriginalName());
        //     $video = $video->getClientOriginalName();
        // }

        $request->merge([
            'slug' => Str::slug($request->title),
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'status_id' => $request->status_id,
        ]);

        $posts = Article::create($request->all());
        if (!$posts) {
            return response()->json([
                'message' => 'Failed Create Article'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success Create Article',
                'data' => new PostsResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
        ]);
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $posts->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'status' => $request->status,
            ]);
            return response()->json([
                'message' => 'Success Update Article',
                'data' => new PostsResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function destroy($id)
    {
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $posts->delete();
            return response()->json([
                'message' => 'Success Delete Article',
            ], 200);
        }
    }

    public function search(Request $request)
    {
        $posts = Article::where('title', 'like', '%' . $request->search . '%')->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success Search Article',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,fullname', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function showByUser($id)
    {
        $post = Article::where('user_id', $id)->get();
        if ($user = User::find($id)) {
            if (!$post || $post->isEmpty()) {
                return response()->json([
                    'message' => 'Article Not Found'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Success View All Article By User',
                    'data' => PostsResource::collection($post->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'User Not Found'
            ], 404);
        }
    }

    public function showByStatus($id)
    {
        if ($status = Status::find($id)) {
            $posts = Article::where('status_id', $id)->get();
            if (!$posts || $posts->isEmpty()) {
                return response()->json([
                    'message' => 'Article Not Found'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Success Search Post By Status',
                    'data' => PostsResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Status Not Found'
            ], 404);
        }
    }

    public function showByCategory($id)
    {
        if ($category = Category::find($id)) {
            $posts = Article::where('category_id', $id)->get();
            if (!$posts || $posts->isEmpty()) {
                return response()->json([
                    'message' => 'Article Not Found'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Success Search Post By Category',
                    'data' => PostsResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Category Not Found'
            ], 404);
        }
    }

    public function showTrash($id)
    {
        $posts = Article::onlyTrashed()->find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View Article Trash',
                'data' => new PostsResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function showTrashAll()
    {
        $posts = Article::onlyTrashed()->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View All Article Trash',
                'data' => PostsResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function restore($id)
    {
        $posts = Article::onlyTrashed()->find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $posts->restore();
            return response()->json([
                'message' => 'Success Restore Article',
                'data' => new PostsResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function forceDelete($id)
    {
        $posts = Article::onlyTrashed()->find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $posts->forceDelete();
            return response()->json([
                'message' => 'Success Force Delete Article',
            ], 200);
        }
    }
}
