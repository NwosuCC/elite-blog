<?php

namespace App;


class Role extends Model
{
    protected $fillable = [
        'name', 'slug', 'description'
    ];

    const DEFAULT_ROLES = [0,1,2];
    const ADMIN_ROLE = 'admin';

    public function getRouteKeyName() {
        return 'slug';
    }

    public function getIsDefaultAttribute() {
        return in_array($this->getAttribute('rank'), static::DEFAULT_ROLES);
    }


    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
