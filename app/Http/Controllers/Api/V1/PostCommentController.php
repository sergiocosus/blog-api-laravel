<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\Post\Comment;
use App\Core\Post\Post;
use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Comment\CreateCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller {
    /**
     * Return the post's comments.
     */
    public function index(Request $request, Post $post) {
        return $post->comments()
            ->with('author')
            ->latest()
            ->paginate($request->get('limit', 20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommentRequest $request, Post $post) {
        $comment = Auth::user()->comments()->create([
            'post_id' => $post->id,
            'content' => $request->input('content'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])->load('author');;

        broadcast(new CommentPosted($comment, $post))->toOthers();

        return compact('comment');
    }

    /**
     * Return the specified resource.
     */
    public function show(Comment $comment) {
        return compact('comment');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment) {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }
}
