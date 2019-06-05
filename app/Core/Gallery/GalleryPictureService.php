<?php


namespace App\Core\Gallery;


use Illuminate\Http\Request;

class GalleryPictureService {
    public function create(Request $request) {
        $gallery_picture = new GalleryPicture();
        $gallery_picture->author()->associate(auth()->user());
        $gallery_picture->gallery()->associate($request->gallery);
        return $this->update($gallery_picture, $request);
    }

    public function update(GalleryPicture $galleryPicture, Request $request) {
        if ($request->title && $galleryPicture->slug) {
            $galleryPicture->slug = null;
        }

        $galleryPicture->fill($request->only([
            'title',
            'slug',
            'content',
        ]));

        if ($request->picture) {
            $galleryPicture->customMediaAdd(
                $request->picture['base64'],
                $request->picture['name']
            );
        }
        $galleryPicture->save();

        return $galleryPicture;
    }
}
