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
            ->paginate($request->get('limit', 20));

        $paginated_media->getCollection()
            ->transform(function ($media) {
                return [
                    'id' => $media->id,
                    'srcset' => $media->getSrcset('media'),
                    'url' => $media->getFullUrl('media'),
                    'name' => $media->name,
                ];
            });

        return compact('paginated_media');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMediaRequest $request) {
        $image = $request->get('base64');
        $name  = $request->get('name');

        $media = MediaLibrary::first()
            ->addMediaFromBase64($image)
            ->usingName($name)
            ->toMediaCollection();

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
