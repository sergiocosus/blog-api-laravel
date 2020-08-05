<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Aug 2020 22:46:00 +0000.
 */

namespace App\Core\Misc;

use App\BaseModel as Eloquent;

/**
 * Class ContactResponse
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ContactResponse extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'name',
		'phone',
		'email',
		'subject',
		'message'
	];
}
