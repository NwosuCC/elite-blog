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


    public function createRole(Role $role) {
        $this->roles()->save($role);
    }

    public function roles() {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasRole($role) {
        if(is_string($role)){ $role = Role::of($role); }
        $role = (array) $role;
        return !! $this->roles()->find( $role )->first();
    }

    public function isAdmin() {
        return $this->hasRole( Role::of('Admin') );
    }

    public function isSuperAdmin() {
        return $this->hasRole( Role::of('SuperAdmin') );
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
