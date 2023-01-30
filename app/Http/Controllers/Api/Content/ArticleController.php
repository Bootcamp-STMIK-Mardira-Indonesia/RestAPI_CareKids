<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\User;
use App\Models\Status;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\ArticleUpdateResource;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
    }
    public function index()
    {
        $posts = Article::all();
        if (!$posts || $posts->count() == 0) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View All Articles',
                'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category'])),
            ], 200);
        }
    }

    public function show($id)
    {
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Success View Article',
                'data' => new ArticleDetailResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status', 'comments', 'images'])),
            ], 200);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'content' => 'required|string',
            'video' => 'mimes:mp4,mov,ogg,qt|max:10000',
        ]);

        $thumbnail = null;
        if ($request->file('thumbnail')) {
            $file_name = $this->generateRandomString();
            $extension = $request->file('thumbnail')->getClientOriginalExtension();
            $thumbnail = $file_name . '.' . $extension;

            Storage::putFileAs('thumbnail', $request->file('thumbnail'), $thumbnail);
        }

        $video = null;
        if ($request->file('video')) {
            $file_name = $this->generateRandomString();
            $extension = $request->file('video')->getClientOriginalExtension();
            $video = $file_name . '.' . $extension;

            Storage::putFileAs('video', $request->file('video'), $video);
        }

        $request->merge([
            'slug' => Str::slug($request->title),
            'user_id' => Auth::user()->id,
            'category_id' => $request->category_id,
            'status_id' => $request->status_id,
        ]);

        $posts = Article::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'thumbnail' => $thumbnail,
            'content' => $request->content,
            'video' => $video,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'status_id' => $request->status_id,
        ]);
        return response()->json([
            'message' => 'Success Create Article',
            'data' => new ArticleResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $posts = Article::find($id);
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'content' => 'required|string',
                'video' => 'mimes:mp4,mov,ogg,qt|max:10000',
            ]);

            $thumbnail = null;
            if ($request->file('thumbnail')) {
                $file_name = $this->generateRandomString();
                $extension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnail = $file_name . '.' . $extension;

                Storage::putFileAs('thumbnail', $request->file('thumbnail'), $thumbnail);
            }

            $video = null;
            if ($request->file('video')) {
                $file_name = $this->generateRandomString();
                $extension = $request->file('video')->getClientOriginalExtension();
                $video = $file_name . '.' . $extension;

                Storage::putFileAs('video', $request->file('video'), $video);
            }

            $request->merge([
                'slug' => Str::slug($request->title),
                'user_id' => Auth::user()->id,
                'category_id' => $request->category_id,
                'status_id' => $request->status_id,
            ]);

            $posts->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'thumbnail' => $thumbnail,
                'content' => $request->content,
                'video' => $video,
            ]);
            return response()->json([
                'message' => 'Success Update Article',
                'data' => new ArticleUpdateResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                'data' => new ArticleResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
            ], 200);
        }
    }

    public function showByUser($id)
    {
        $posts = Article::where('user_id', $id)->get();
        if ($user = User::find($id)) {
            if (!$posts || $posts->isEmpty()) {
                return response()->json([
                    'message' => 'Article Not Found'
                ], 404);
            } else {
                return response()->json([
                    'message' => 'Success View All Article By User',
                    'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                    'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                    'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                'data' => new ArticleResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                'data' => ArticleResource::collection($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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
                'data' => new ArticleResource($posts->LoadMissing(['user:id,full_name', 'category:id,name_category', 'status:id,name_status'])),
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

    public function forceDeleteAll()
    {
        $posts = Article::onlyTrashed()->get();
        if (!$posts) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        } else {
            $posts->forceDelete();
            return response()->json([
                'message' => 'Success Force Delete All Article',
            ], 200);
        }
    }
}
