<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Mar 2019 01:50:06 +0000.
 */

namespace App\Core\Post;

use App\BaseModel as Eloquent;
use App\Core\Like\Likeable;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Str;

/**
 * Class Post
 *
 * @property int $id
 * @property int $author_user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property \Carbon\Carbon $posted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\User $user
 * @property \Illuminate\Database\Eloquent\Collection $comments
 *
 * @package App\Models
 */
class Post extends Eloquent implements HasMedia {
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use Likeable;
    use Sluggable;
    use HasMediaTrait;

    const PUBLISHED = 'published';
    const DRAFT = 'draft';


    protected $casts = [
        'author_user_id' => 'int',
    ];

    protected $dates = [
        'posted_at',
    ];

    protected $fillable = [
        'author_user_id',
        'title',
        'slug',
        'content',
        'posted_at',
    ];

    protected $appends = [
        'image_srcset',
    ];

    public function author() {
        return $this->belongsTo(\App\User::class, 'author_user_id');
    }

    public function comments() {
        return $this->hasMany(\App\Core\Post\Comment::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }


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


    /**
     * Scope a query to order posts by latest posted
     */
    public function scopeLatest(Builder $query) {
        $query->orderBy('posted_at', 'desc');
    }

    /**
     * Scope a query to only include posts posted last month.
     */
    public function scopeLastMonth(Builder $query, int $limit = 5) {
        $query->whereBetween('posted_at', [new Carbon('1 month ago'), now()])
            ->latest()
            ->limit($limit);
    }

    /**
     * Scope a query to only include posts posted last week.
     */
    public function scopeLastWeek(Builder $query) {
        $query->whereBetween('posted_at', [new Carbon('1 week ago'), now()])
            ->latest();
    }

    /**
     * return the excerpt of the post content
     */
    public function excerpt(int $length = 50): string {
        return Str::limit($this->content, $length);
    }

    public function registerMediaConversions(Media $media = null) {
        $this->addMediaConversion('thumb')
            ->withResponsiveImages();
    }

    public function getImageSrcsetAttribute() {
        return optional($this->getMedia('thumb')
            ->last())->getSrcset('thumb');

    }

}
