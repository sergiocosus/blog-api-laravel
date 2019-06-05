<?php


namespace App\Core\Gallery;


use Illuminate\Http\Request;

class GalleryService {
    public function create(Request $request) {
        $gallery = new Gallery();
        $gallery->author()->associate(auth()->user());
        return $this->update($gallery, $request);
    }

    public function update(Gallery $gallery, Request $request) {
        $gallery->fill($request->only([
            'title',
            'slug',
            'content',
        ]));

        if ($request->picture) {
            $gallery->customMediaAdd(
                $request->picture['base64'],
                $request->picture['name']
            );
        }

        $gallery->save();

        return $gallery;
    }
}
