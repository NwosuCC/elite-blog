<?php

namespace App;


use App\Events\PostSaved;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'slug', 'published_at'
    ];

    protected $events = [
      'created' => PostSaved::class,
      'updated' => PostSaved::class,
      'deleting' => PostSaved::class,
      'deleted' => PostSaved::class,
    ];


    protected static function boot() {
        parent::boot();

        // ToDo: find a more standard fix for this filter (posts whose category is deleted must NOT be retrieved)
        static::addGlobalScope('category', function (Builder $builder){
           $builder->whereIn('category_id', Category::pluck('id'));
        });
    }


    public function getRouteKeyName() {
        return 'slug';
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function category() {
        return $this->belongsTo(Category::class);
    }


    public function scopeIn($query, $category = null){
        return $category ? $query->where('category_id', $category->id) : null;
    }


    public function scopePublished($query){
        if($published_at = $this->getAttribute('published_at')) {
            return Carbon::make( $published_at )->isPast();
        }

        return $query->where('published_at', '<=', Carbon::now());
    }


    public function author() {
        return auth()->id() === $this->id;
    }

}
