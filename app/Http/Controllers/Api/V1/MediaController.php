<?php

namespace App\Http\Controllers\Api\V1;

use App\Core\MediaLibrary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMediaRequest;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\Models\Media;

class MediaController extends Controller
{
    /**
     * Return the comments.
     */
    public function index(Request $request): ResourceCollection
    {
        $paginated_media = MediaLibrary::first()
            ->media()
            ->paginate($request->get('limit', 20));

        return compact('paginated_media');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMediaRequest $request)
    {
        $this->authorize('store', Media::class);

        $image = $request->get('base64');
        $name = $request->get('name');

        return MediaLibrary::first()
                        ->addMediaFromBase64($image)
                        ->usingName($name)
                        ->toMediaCollection();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media): Response
    {
        $this->authorize('delete', $media);

        $media->delete();

        return response()->noContent();
    }
}
