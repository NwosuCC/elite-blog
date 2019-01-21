<?php

namespace App;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description'
    ];


    public function getRouteKeyName() {
        return 'slug';
    }



    public function isActive(){
        return ! $this->getDeletedAtColumn();
    }

    public function posts() {
        return $this->hasMany(Post::class)->latest();
    }

    public function addPost(Post $post) {
        $post->setAttribute('category_id', $this->getAttribute('id'));
        return $post;
    }


}
