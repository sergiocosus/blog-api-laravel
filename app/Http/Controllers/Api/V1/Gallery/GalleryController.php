<?php


namespace App\Http\Controllers\Api\V1\Gallery;


use App\Core\Gallery\Gallery;
use App\Core\Gallery\GalleryPicture;
use App\Core\Gallery\GalleryService;
use App\Http\Requests\Gallery\CreateGalleryRequest;
use App\Http\Requests\Gallery\DeleteGalleryRequest;
use App\Http\Requests\Gallery\UpdateGalleryRequest;
use Illuminate\Http\Request;

class GalleryController {
    /**
     * @var GalleryService
     */
    private $galleryService;

    /**
     * GalleryController constructor.
     */
    public function __construct(GalleryService $galleryService) {
        $this->galleryService = $galleryService;
    }


    /**
     * Return the posts.
     */
    public function index(Request $request) {
        $paginated_galleries = Gallery::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })
           // ->with('gallery_pictures')
            ->latest()
            ->paginate($request->get('per_page', 20));

        return compact('paginated_galleries');
    }

    public function getOne(Request $request, Gallery $gallery) {
        $gallery->load([
            'author', 'gallery_pictures',
        ]);

        return compact('gallery');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateGalleryRequest $request) {
        $gallery = $this->galleryService->create($request)
            ->load('author', 'gallery_pictures');

        return compact('gallery');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery) {
        $this->galleryService->update($gallery, $request)
            ->load('author', 'gallery_pictures');

        return compact('gallery');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteGalleryRequest $request, Gallery $gallery) {
        $gallery->delete();

        return response()->noContent();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($gallery_slug) {
        $gallery = Gallery::whereSlug($gallery_slug)
            ->withTrashed()->first();
        $gallery->restore();

        return compact('gallery');
    }
}
