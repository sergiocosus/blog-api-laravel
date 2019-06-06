<?php


namespace App\Http\Controllers\Api\V1\Gallery;


use App\Core\Gallery\Gallery;
use App\Core\Gallery\GalleryPicture;
use App\Core\Gallery\GalleryPictureService;
use App\Http\Requests\Gallery\CreateGalleryPictureRequest;
use App\Http\Requests\Gallery\DeleteGalleryPictureRequest;
use App\Http\Requests\Gallery\UpdateGalleryPictureRequest;
use Illuminate\Http\Request;

class GalleryPictureController {
    /**
     * @var GalleryPictureService
     */
    private $galleryPictureService;

    /**
     * GalleryController constructor.
     */
    public function __construct(GalleryPictureService $galleryPictureService) {
        $this->galleryPictureService = $galleryPictureService;
    }


    /**
     * Return the posts.
     */
    public function index(Request $request, Gallery $gallery) {
        $paginated_posts = $gallery->gallery_pictures()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })
            ->latest()
            ->paginate($request->get('per_page', 20));

        return compact('paginated_posts');
    }

    public function getOne(Request $request, GalleryPicture $gallery_picture) {
        $gallery_picture->load([
            'author',
        ]);

        return compact('gallery_picture');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateGalleryPictureRequest $request,  Gallery $gallery) {
        $gallery_picture = $this->galleryPictureService->create($request)
            ->load('author');

        return compact('gallery_picture');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryPictureRequest $request, GalleryPicture $gallery_picture) {
        $this->galleryPictureService->update($gallery_picture, $request)
            ->load('author');

        return compact('gallery_picture');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteGalleryPictureRequest $request, GalleryPicture $gallery_picture) {
        $gallery_picture->delete();

        return response()->noContent();
    }


    /**
     * Restore the specified resource from storage.
     */
    public function restore($gallery_picture_slug) {
        $gallery_picture = GalleryPicture::whereSlug($gallery_picture_slug)
            ->withTrashed()->first();
        $gallery_picture->restore();

        return compact('gallery_picture');
    }
}
