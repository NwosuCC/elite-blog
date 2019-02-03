<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends Eloquent
{
    protected $guarded = [];

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function getFormValuesAttribute() {
        return json_encode([
            'routeKey' => $this->getRouteKey(),
            'fields' => $this->only($this->fillable),
        ]);
    }


    protected static $errors = [
        'no_action' => 'You are not authorized to perform this action',
        'no_modify' => 'You do not have permission to modify this {model}',
    ];

    const ERROR_NO_ACTION = 'no_action';
    const ERROR_NO_MODIFY = 'no_modify';


    public static function error($key, $class = ''){
        $error_message = retry(1, function() use ($key) {
            return static::$errors[$key];
        });

        if($class) {
            $model = strtolower( class_basename($class) );

            $error_message = str_replace('{model}', $model, $error_message);
        }

        return $error_message;
    }


}
