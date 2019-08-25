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


    public function user() {
        return $this->belongsTo(User::class);
    }


    /*public static function scopeActive($query){
        return $query->whereNull( (new static)->getDeletedAtColumn().'s' );
    }*/


    public function posts() {
        return $this->hasMany(Post::class)->latest();
    }

    public function addPost(Post $post)
    {
      return $post->setAttribute('category_id', $this->{'id'});
    }


}
