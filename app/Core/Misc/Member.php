<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 27 Jun 2020 02:26:47 +0000.
 */

namespace App\Core\Misc;

use App\BaseModel as Eloquent;
use App\Core\Media\CommonMediaAdderTrait;
use App\Core\Media\DefaultMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Member
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Member extends Eloquent implements HasMedia
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
    use HasMediaTrait, CommonMediaAdderTrait, DefaultMediaTrait {
        DefaultMediaTrait::registerMediaConversions insteadof HasMediaTrait;
    }

	protected $fillable = [
		'name',
		'order'
	];

    protected $hidden = ['media'];

    protected $appends = [
        'image_srcset',
        'image_url',
    ];
}
