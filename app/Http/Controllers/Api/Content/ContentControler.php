<?php

namespace App\Http\Controllers\Api\Content;

use App\Models\Post;
use App\Models\Articles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostsResource;
use Illuminate\Support\Facades\Storage;


class ContentControler extends Controller
{
    public function index()
    {
        // $posts = Articles::all();
        // return PostsResource::collection($posts->LoadMissing(['writer:id,name', 'category:id,name_category']));
    }

    public function show($id)
    {
        // $post = Articles::findOrFaild($id);
        // return new PostsResource($post->LoadMissing(['writer:id,name', 'category:id,name_category']));
    }
}
