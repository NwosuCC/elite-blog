<?php

namespace App;


class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'description'
    ];

    /**
     * Certain Roles are present in (almost) any application by default
     * Below are the associated rank numbers for the (assumed) default roles
     *
     * 0 - Guest (has no rank)
     * 1 - SuperAdmin (has highest rank)
     * 2 - Admin
     * 3 - User
     *
     * Custom Roles can then be created and assigned ranks 4, 5, 6, ... etc
     */
    const DEFAULT_RANKS = [0,1,2,3];


    public function getRouteKeyName() {
        return 'slug';
    }


    public function scopeIsDefault($query){
        if($rank = $this->getAttribute('rank')) {
            return in_array($rank, static::DEFAULT_RANKS);
        }

        return $query->whereIn('rank', static::DEFAULT_RANKS);
    }

    public function admin() {
        return static::where()
    }


    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
