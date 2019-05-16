<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 15 May 2019 18:04:52 +0000.
 */

namespace App\Core\Link;

use App\BaseModel as Eloquent;
use App\User;

/**
 * Class Link
 * 
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Link extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $casts = [
        'creator_user_id' => 'int'
    ];

    protected $fillable = [
		'title',
		'url',
		'description'
	];

    public function user()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }
}
