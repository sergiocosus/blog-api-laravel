<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 May 2019 18:35:30 +0000.
 */

namespace App\Core;

use App\BaseModel as Eloquent;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class PageSetting
 * 
 * @property int $id
 * @property string $name
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class PageSetting extends Eloquent implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
		'name',
		'content'
	];

    protected $appends = [
        'image_srcset',
        'image_url',
    ];

	public static $validConfigs = [
	    [
            'name' => 'title',
            'type' => 'text',
        ],
        [
            'name' => 'contact',
            'type' => 'longText',
        ],
	    [
            'name' => 'showLinks',
            'type' => 'boolean',
        ],
	    [
            'name' => 'showEvents',
            'type' => 'boolean',
        ],
	    [
            'name' => 'showContact',
            'type' => 'boolean',
        ],
	    [
            'name' => 'mainPagePicture',
            'type' => 'picture',
        ],
    ];


    public function registerMediaConversions(Media $media = null) {
        $this->addMediaConversion('main')
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
