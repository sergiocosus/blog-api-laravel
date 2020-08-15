<?php


namespace App\Core\Link;




class LinkService {

    public function createLink($data) {
        $link = new Link();
        $link->user()->associate(auth()->user());

        return $this->updatePost($link, $data);
    }

    public function updatePost(Link $link, $data) {
        $link->fill($data)->save();
        $link->categories()->sync($data['category_ids']);

        return $link;
    }
}
