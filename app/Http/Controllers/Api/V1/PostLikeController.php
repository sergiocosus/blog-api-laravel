<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\Post\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request, Post $post)
    {
        $like = $post->like();

        return compact('like');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $like = $post->dislike();

        return compact('like');
    }
}
