<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 22 Jul 2020 03:31:53 +0000.
 */

namespace App\Core\Misc;

use App\BaseModel as Eloquent;

/**
 * Class Organization
 *
 * @property int $id
 * @property string $name
 * @property string $facebook
 * @property string $instagram
 * @property string $twitter
 * @property string $youtube
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Organization extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'name',
		'facebook',
		'instagram',
		'twitter',
		'youtube',
		'phone',
		'email',
		'address'
	];
}
