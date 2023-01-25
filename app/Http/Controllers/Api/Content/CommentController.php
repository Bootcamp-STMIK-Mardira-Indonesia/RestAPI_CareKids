<?php

namespace App\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            'message' => 'Success View All Comments',
            'data' => $comments,
        ], 200);
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'comment' => 'required|string',
        ]);

        $comment = Comment::create([
            'article_id' => $request->article_id,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Success Create Comment',
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
