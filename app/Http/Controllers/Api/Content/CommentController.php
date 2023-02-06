<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'store', 'update');
    }

    public function index()
    {
        $comments = Comment::all();
        if (!$comments || $comments->count() == 0) {
            return response()->json([
                'message' => 'Comment Not Found'
            ], 404);
        }
        return response()->json([
            'message' => 'Success View All Comments',
            'data' => $comments,
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'comment' => 'required|string',
            'article_id' => 'required|integer',
        ]);
        if (!Article::find($request->article_id)) {
            return response()->json([
                'message' => 'Article Not Found'
            ], 404);
        }
        $comment = Comment::create([
            'name' => $request->name,
            'comment' => $request->comment,
            'article_id' => $request->article_id,
        ]);
        return response()->json([
            'message' => 'Success Create Comment',
            'data' => $comment,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->update([
            'comment' => $request->comment,
        ]);
        return response()->json([
            'message' => 'Success Update Comment',
            'data' => $comment,
        ], 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->delete();
        return response()->json([
            'message' => 'Success Delete Comment',
            'data' => $comment,
        ], 200);
    }

    public function forceDelete($id)
    {
        $comment = Comment::onlyTrashed()->find($id);
        if (!$comment) {
            return response()->json([
                'message' => 'Comment Not Found'
            ], 404);
        }
        $comment->forceDelete();
        return response()->json([
            'message' => 'Success Force Delete Comment',
            'data' => $comment,
        ], 200);
    }
}
