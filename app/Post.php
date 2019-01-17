<?php

namespace App;

use ErrorException;
use Exception;
use function foo\func;

class Post extends Model
{
    protected $fillable = [
        'title', 'body', 'slug'
    ];


    private static $errors = [
        'no_modify' => 'You do not have permission to alter this article',
    ];

    public const ERROR_NO_MODIFY = 'no_modify';


    public static function error($key){
        $error_message = retry(1, function() use ($key) {
            return static::$errors[$key];
        });

        /*try {
            $error_message = static::$errors[$key];
        }
        catch (ErrorException $e) {
            throw new Exception("Unknown error key '{$key}' in " . $e->getFile() . ' ' . $e->getLine());
        }*/

        return $error_message;
    }


    public function getRouteKeyName() {
        return 'slug';
    }


    public function user() {
        return $this->belongsTo(User::class);
    }


    public function author() {
        return auth()->user()->posts()->find($this->only('id'))->first();
    }

}
