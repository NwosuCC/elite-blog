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


    public function getRouteKeyName() {
        return 'slug';
    }

    public function roles() {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function createRole(Role $role) {
        $this->roles()->save($role);
    }

    public function hasRole(Role $role) {
//        return $this->roles()->where('slug', $role)->exists();
        return $this->roles()->has( $role );
    }

    public function isAdmin() {
        return $this->hasRole( Role::admin() );
    }

    public function isSuperAdmin() {
        return false;
    }


    public function createCategory(Category $category) {
        $this->categories()->save($category);
    }

    public function categories() {
        return $this->hasMany(Category::class)->latest();
    }


    public function publishPost(Category $category, Post $post){
        $this->posts()->save( $category->addPost($post) );
    }

    public function posts() {
        return $this->hasMany(Post::class)->latest();
    }

}
