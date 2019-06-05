<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 16 May 2019 16:00:18 +0000.
 */

namespace App\Core\Event;

use App\BaseModel as Eloquent;
use App\Core\Media\CommonMediaAdderTrait;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class Event
 * 
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $address
 * @property \Carbon\Carbon $begin_at
 * @property \Carbon\Carbon $end_at
 * @property \Carbon\Carbon $notify_at
 * @property float $latitude
 * @property float $longitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Event extends Eloquent implements HasMedia
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    use Sluggable;
    use HasMediaTrait;
    use CommonMediaAdderTrait;

	protected $casts = [
		'latitude' => 'float',
		'longitude' => 'float'
	];

	protected $dates = [
		'begin_at',
		'end_at',
		'notify_at'
	];

	protected $fillable = [
		'title',
		'slug',
		'description',
		'address',
		'begin_at',
		'end_at',
		'notify_at',
		'latitude',
		'longitude'
	];

    protected $appends = [
        'image_srcset',
        'image_url',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string {
        return 'slug';
    }


    public function author()
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }

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
