<?php


namespace App\Http\Controllers\Api\V1\Post;


use App\Core\Post\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller {

    /**
     * Return the posts.
     */
    public function index(Request $request) {
        $categories = Category::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search, null, true, true);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })->get();

        return compact('categories');
    }

    public function getOne(Request $request, Category $category) {
        $category->load('posts');

        return compact('category');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) {
        $category->update($request->only([
            'title',
            'content',
        ]));

        return compact('category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $category = (new Category())->fill($request->only([
            'title',
            'content',
        ]));

        $category->save();

        return compact('category');
    }

    /**
     * Return the specified resource.
     */
    public function show(Category $category) {
        return compact('category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): Response {
        $category->delete();

        return response()->noContent();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($category_slug) {
        $category = Category::whereSlug($category_slug)->withTrashed()->first();
        $category->restore();

        return compact('category');
    }
}
