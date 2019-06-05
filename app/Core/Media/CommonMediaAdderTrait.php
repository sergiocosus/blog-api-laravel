<?php


namespace App\Core\Media;


trait CommonMediaAdderTrait {

    public function customMediaAdd($base64, $name, $collection = 'main') {
        $this->addMediaFromBase64($base64)
            ->preservingOriginal()
            ->setFileName($name)
            ->setName($name)
            ->toMediaCollection($collection);
    }
}
