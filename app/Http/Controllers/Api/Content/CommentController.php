<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show', 'store', 'update');
    }

    public function index()
    {
        $comments = Comment::all();
        if (!$comments || $comments->count() == 0) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Comment Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View All Comments',
            'data' => $comments,
        ], 200);
    }

    public function show($article_id)
    {
        $comments = Comment::where('article_id', $article_id)->get();
        if (!$comments || $comments->count() == 0) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Comment Not Found'
            ], 404);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Success View All Comments This Article',
            'data' => CommentResource::collection($comments),
        ], 200);
    }

    public function store(Request $request, $article_id)
    {
        $request->validate([
            'name' => 'required|string',
            'comment' => 'required|string',
        ]);

        $article = Article::find($article_id);
        if (!$article) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Article Not Found'
            ], 404);
        }
        $comment = Comment::create([
            'name' => $request->name,
            'comment' => $request->comment,
            'article_id' => $article_id,
        ]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Create Comment',
            'data' => $comment,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->update([
            'comment' => $request->comment,
        ]);
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Update Comment',
            'data' => $comment,
        ], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Delete Comment',
            'data' => $comment,
        ], 200);
    }

    public function forceDelete($id)
    {
        $comment = Comment::onlyTrashed()->find($id);
        if (!$comment) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->forceDelete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Success Force Delete Comment',
            'data' => $comment,
        ], 200);
    }
}
