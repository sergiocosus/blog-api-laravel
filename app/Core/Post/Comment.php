<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Mar 2019 01:50:06 +0000.
 */

namespace App\Core\Post;

use App\BaseModel as Eloquent;
use App\Core\Like\Likeable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $author_user_id
 * @property int $post_id
 * @property string $content
 * @property \Carbon\Carbon $posted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\User $user
 * @property \App\Core\Post\Post $post
 *
 * @package App\Models
 */
class Comment extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    use Likeable;

	protected $casts = [
		'author_user_id' => 'int',
		'post_id' => 'int'
	];

	protected $dates = [
		'posted_at'
	];

	protected $fillable = [
		'author_user_id',
		'post_id',
		'content',
		'posted_at',
		'ip_address',
		'user_agent'
	];

	public function author()
	{
		return $this->belongsTo(\App\User::class, 'author_user_id');
	}

	public function post()
	{
		return $this->belongsTo(\App\Core\Post\Post::class);
	}

    /**
     * Scope a query to only include comments posted last week.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->whereBetween('posted_at', [new Carbon('1 week ago'), now()])
            ->latest();
    }

    /**
     * Scope a query to order comments by latest posted.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('posted_at', 'desc');
    }
}
