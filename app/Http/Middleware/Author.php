<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostsResource;

class Author
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $curentUser = Auth::user();
        $post = Article::find($request->id);
        if ($curentUser->id != $post->user_id) {
            return response()->json([
                'message' => 'You are not the author of this article'
            ], 403);
        }
        return response()->json($curentUser->id);
        //return $next($request);
    }
}
