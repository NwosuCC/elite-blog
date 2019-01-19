<?php

namespace App;


class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'slug'
    ];


    public function getRouteKeyName() {
        return 'slug';
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function scopeInCategory($query, $category){
        return $query->where('category_id', $category->id);
    }


    public function author() {
        return auth()->user()->posts()->find($this->only('id'))->first();
    }

}
