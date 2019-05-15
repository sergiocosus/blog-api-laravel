<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 14 May 2019 18:35:30 +0000.
 */

namespace App\Models;

use App\BaseModel as Eloquent;

/**
 * Class PageSetting
 * 
 * @property int $id
 * @property string $name
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class PageSetting extends Eloquent
{
	protected $fillable = [
		'name',
		'content'
	];

	public static $validConfigs = [
	    [
            'name' => 'title',
            'type' => 'text',
        ]
    ];
}
