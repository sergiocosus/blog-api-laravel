<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Mar 2019 01:50:06 +0000.
 */

namespace App\Core\Like;

use App\BaseModel as Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Like
 * 
 * @property int $id
 * @property int $author_user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Core\User $user
 *
 * @package App\Models
 */
class Like extends Eloquent
{
	protected $casts = [
		'author_user_id' => 'int',
		'likeable_id' => 'int'
	];

	protected $fillable = [
		'author_user_id',
		'likeable_type',
		'likeable_id'
	];

	public function author()
	{
		return $this->belongsTo(\App\Core\User::class, 'author_user_id');
	}


    /**
     * Get all of the owning likeable models.
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
