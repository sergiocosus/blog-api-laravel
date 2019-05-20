<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\MediaLibrary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CreateMediaRequest;
use App\Http\Requests\Media\DeleteMediaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller {
    /**
     * Return the comments.
     */
    public function index(Request $request) {
        $paginated_media = MediaLibrary::first()
            ->media()
            ->when($request->search, function ($query, $search) {
                $query->search($search, null, true, true);
            })
            ->paginate($request->get('limit', 20));

        $paginated_media->getCollection()
            ->transform(function ($media) {
                return $media->toMediaResponse();
            });

        return compact('paginated_media');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMediaRequest $request) {
        $picture = $request->picture;

        $media = MediaLibrary::first()
            ->addMediaFromBase64($picture['base64'])
            ->usingName($picture['name'])
            ->toMediaCollection();
        $media = \App\Core\Media\Media::find($media->id)
            ->toMediaResponse();

        return compact('media');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteMediaRequest $request, Media $media): Response {
        $media->delete();

        return response()->noContent();
    }
}
