<?php


namespace App\Core\Post;


use Illuminate\Http\Request;

class PostService {
    public function createPost(Request $request) {
        $post = new Post();
        $post->author()->associate(auth()->user());
        return $this->updatePost($post, $request);
    }

    public function updatePost(Post $post, Request $request) {
        $post->fill($request->only([
            'title',
            'content',
            'posted_at',
            'thumbnail_id',
        ]));

        if ($request->thumbnail) {
            $post->addMediaFromBase64($request->thumbnail['base64'])
                ->preservingOriginal()
                ->setName($request->thumbnail['name'])
                ->toMediaCollection('main');;
        }
        $post->save();

        $post->categories()->sync($request->category_ids);

        return $post;
    }
}
