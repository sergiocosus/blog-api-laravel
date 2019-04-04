<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/03/19
 * Time: 11:45 AM
 */

namespace App\Core\Like;


use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Likeable {
    /**
     * The "booting" method of the trait.
     */
    protected static function bootLikeable(): void
    {
        static::deleting(function ($resource) {
            $resource->likes->each->delete();
        });
    }

    /**
     * Get all of the resource's likes.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Create a like if it does not exist yet.
     */
    public function like()
    {
        if ($this->likes()->where('author_id', auth()->id())->doesntExist()) {
            return $this->likes()->create(['author_id' => auth()->id()]);
        }
    }

    /**
     * Check if the resource is liked by the current user
     */
    public function isLiked(): bool
    {
        return $this->likes->where('author_id', auth()->id())->isNotEmpty();
    }

    /**
     * Delete like for a resource.
     */
    public function dislike()
    {
        return $this->likes()->where('author_id', auth()->id())->get()->each->delete();
    }
}
