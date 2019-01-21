<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'slug'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const ADMIN_ROLE = 'admin';


    public function getRouteKeyName() {
        return 'slug';
    }


    public function isAdmin() {
        return $this->getAttribute('role') === static::ADMIN_ROLE;
    }


    public function categories() {
        return $this->hasMany(Category::class)->latest();
    }

    public function createCategory(Category $category) {
        $this->categories()->save($category);
    }


    public function posts() {
        return $this->hasMany(Post::class)->latest();
    }

    public function publishPost(Category $category, Post $post){
        $this->posts()->save( $category->addPost($post) );
    }

}
