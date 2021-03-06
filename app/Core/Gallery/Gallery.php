<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 24 May 2019 18:26:45 +0000.
 */

namespace App\Core\Gallery;

use App\BaseModel as Eloquent;
use App\Core\Media\CommonMediaAdderTrait;
use App\Core\Media\DefaultMediaTrait;
use App\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Gallery
 *
 * @property int $id
 * @property int $author_user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class Gallery extends Eloquent implements HasMedia {
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use Sluggable;
    use HasMediaTrait, CommonMediaAdderTrait, DefaultMediaTrait {
        DefaultMediaTrait::registerMediaConversions insteadof HasMediaTrait;
    }

    protected $casts = [
        'author_user_id' => 'int',
    ];

    protected $fillable = [
        'author_user_id',
        'title',
        'slug',
        'content',
    ];

    protected $appends = [
        'image_srcset',
        'image_url',
    ];

    protected $hidden = ['media'];

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

    public function author() {
        return $this->belongsTo(User::class, 'author_user_id');
    }

    public function gallery_pictures() {
        return $this->hasMany(GalleryPicture::class);
    }
}
