<?php

namespace App\Core\Post;

trait CategorizableTrait
{
    public function categories() {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
