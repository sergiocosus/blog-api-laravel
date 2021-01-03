<?php

namespace App\Common;

trait SearchableTrait
{
    use \Nicolaslopezj\Searchable\SearchableTrait;

    public function scopeScopedSearch($query, $search)
    {
        $ids = self::search($search, null, true, true)->pluck('id');
        $query->whereIn($this->getTable(). '.id', $ids);
    }

    public function scopeWhenScopedSearch($query, $search)
    {
        if ($search) {
            $query->scopedSearch($search);
        }
    }
}
