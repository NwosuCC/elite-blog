<?php

namespace App;


use Carbon\Carbon;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'slug', 'published_at'
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


    public function scopeFilter($query, $category = null){
        $categoryGroup = $category
            ? array_values( $category->only('id') )
            : Category::all()->pluck('id');

        return $query->whereIn('category_id', $categoryGroup);
    }


    public function scopePublished($query){
        if($published_at = $this->getAttribute('published_at')) {
            return Carbon::make( $published_at )->isPast();
        }

        return $query->where('published_at', '<=', Carbon::now());
    }


    public function author() {
        return auth()->user()->posts()->find($this->only('id'))->first();
    }

}
