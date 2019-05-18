<?php


namespace App\Core\Media;


use Nicolaslopezj\Searchable\SearchableTrait;

class Media extends \Spatie\MediaLibrary\Models\Media {

    use SearchableTrait;


    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'media.name' => 10,
            'media.file_name' => 10,
        ],
    ];

    public function toMediaResponse() {
        return  [
            'id' => $this->id,
            'srcset' => $this->getSrcset('media'),
            'url' => $this->getFullUrl('media'),
            'name' => $this->name,
        ];
    }
}
