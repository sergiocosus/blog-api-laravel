<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 17 Mar 2019 23:15:27 +0000.
 */

namespace App\Core\Post;

use App\BaseModel as Eloquent;
use Cviebrock\EloquentSluggable\Sluggable;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $posts
 *
 * @package App\Models
 */
class Category extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    use Sluggable;
    use SearchableTrait;

	protected $fillable = [
		'title',
		'slug'
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
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'categories.title' => 10,
        ],
    ];

	public function posts()
	{
		return $this->belongsToMany(Post::class);
	}

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string {
        return 'slug';
    }



}
