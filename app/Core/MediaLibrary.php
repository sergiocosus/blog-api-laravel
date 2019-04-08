<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Mar 2019 20:26:03 +0000.
 */

namespace App\Core;

use App\BaseModel as Eloquent;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

/**
 * Class MediaLibrary
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class MediaLibrary extends Eloquent implements HasMedia
{
    use HasMediaTrait;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('media')
            ->withResponsiveImages();
    }
}
