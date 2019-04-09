<?php

namespace App;

use App\Core\DateFormatTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use DateFormatTrait;
}
