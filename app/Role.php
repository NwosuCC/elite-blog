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
    const DEFAULT_RANKS = [ 1, 2 ];

    const SUPER_ADMIN = 1, ADMIN = 2;

    /**
     * Rank numbers for Admin and SuperAdmin roles
     */
    private const ADMIN_RANKS = [
        self::SUPER_ADMIN, self::ADMIN
    ];

    private static $class;
    private static $definition = [];

    /**
     * Register the existing roles in the Class
     *
     * The registered roles can be accessed dynamically by calling the method 'of()'
     * E.g  Role::of('Admin', 'id') will return [1,2] (IDs of both Admin and SuperAdmin)
     *      Role::of('Admin', 'slug') will return 'admin'
     */
    private static function definition() {
        if(!static::$class) {
            static::$class = new static();

            $roles = static::all();

            $admin_roles = $roles->filter(function ($role){
                return in_array($role->rank, static::ADMIN_RANKS);
            });

            $roles->each(function (Role $role) use ($admin_roles) {
                $name = $role->getAttribute('name');

                $roleFunc = static::getFuncName($name);

                static::$definition[ $roleFunc ] = $role->toArray();

                static::$class->$roleFunc = function ($key) use ($roleFunc, $role, $admin_roles) {
                    return ($role->rank === static::ADMIN && $key === 'id')
                        ? $admin_roles->map(function ($value) use($key) { return $value->$key; })->toArray()
                        : static::$definition[$roleFunc][$key];
                };

            });
        }
    }

    private static function getFuncName($roleName) {
        return preg_replace('/[^A-z0-9]/', '', strtolower($roleName));
    }

    /**
     * Returns the IDs or Slugs of the specified role
     * @param string $role The role group to get its ID|Slug
     * @param string $key  The key to pluck. One of 'id' | 'slug'
     * @return mixed
     */
    public static function of($role, $key = 'id') {
        static::definition();

        $roleFunc = static::getFuncName($role);

        return is_callable(static::$class->$roleFunc) ? call_user_func(static::$class->$roleFunc, $key) : '';
    }

    public static function _($role) {
        return static::of($role, 'slug');
    }

    public static function str($role) {
        return ($role = static::_($role)) ? 'role:' . $role : '';
    }


    public function getRouteKeyName() {
        return 'slug';
    }


    public function scopeIsDefault($query){
        if($rank = $this->getAttribute('rank')) {
            return in_array($rank, static::DEFAULT_RANKS);
        }

        return $query->whereIn('rank', static::DEFAULT_RANKS);
    }

    public function scopeRanked($query){
        return $query->orderBy('rank', 'asc');
    }


    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
