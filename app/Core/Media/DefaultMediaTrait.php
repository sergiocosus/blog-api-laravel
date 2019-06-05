<?php


namespace App\Core\Media;


use Spatie\MediaLibrary\Models\Media;

trait DefaultMediaTrait {

    public function registerMediaConversions(Media $media = null) {
        $this->addMediaConversion('main')
            ->keepOriginalImageFormat()
            ->withResponsiveImages();
    }

    public function getImageSrcsetAttribute() {
        return optional($this->getMedia('main')
            ->last())->getSrcset('main');

    }

    public function getImageUrlAttribute() {
        return optional($this->getMedia('main')
            ->last())->getFullUrl('main');

    }
}
