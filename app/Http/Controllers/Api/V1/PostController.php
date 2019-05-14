<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\Post\Post;
use App\Core\Post\PostService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreatePostRequest;
use App\Http\Requests\Post\DeletePostRequest;
use App\Http\Requests\Post\GetPostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller {
    /**
     * @var PostService
     */
    private $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }


    /**
     * Return the posts.
     */
    public function index(Request $request) {
        $paginated_posts = Post::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->get('is_published', 'true') === 'true',
                function ($query) {
                    $query->whereIsPublished(true);
                }, function ($query) use ($request) {
                    throw_unless($request->user()->can('read-post'), \Exception::class);
                })
            ->withCount('comments')
            ->with('categories')
            ->latest()
            ->paginate($request->get('per_page', 20));

        return compact('paginated_posts');
    }

    public function getOne(GetPostRequest $request, Post $post) {
        $post->load([
            'author',
            'comments' => function ($query) {
                $query->with('author')
                    ->orderBy('created_at', 'desc');
            },
            'categories',
        ]);

        return compact('post');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePostRequest $request) {
        $post = $this->postService->createPost($request)
            ->load('author');

        return compact('post');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post) {
        $this->postService->updatePost($post, $request);

        return compact('post');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletePostRequest $request, Post $post): Response {
        $post->delete();

        return response()->noContent();
    }
}
