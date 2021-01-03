<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 27 Jun 2020 18:35:36 +0000.
 */

namespace App\Core\Misc;

use App\BaseModel as Eloquent;

/**
 * Class Argument
 *
 * @property int $id
 * @property string $question
 * @property string $answer
 * @property int $order
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Argument extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \App\Common\SearchableTrait;

	protected $casts = [
		'order' => 'int'
	];

	protected $fillable = [
		'question',
		'answer',
		'order'
	];

    protected $searchable = [
        'columns' => [
            'arguments.question' => 5,
            'arguments.answer' => 5
        ],
    ];
}
