<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Presenters\ModelUrlPresenter;

class Model extends Eloquent
{
    protected $guarded = [];

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'published_at'];


    /** @return static */
    public static function instance()
    {
      return app()->make(static::class);
    }

    public function getRouteAttribute() {
      return new ModelUrlPresenter($this);
    }

    public function getCreateParamsAttribute() {
      return json_encode([
        'route' => $this->route->store,
      ]);
    }

    public function getEditParamsAttribute() {
      return json_encode([
        'fields' => $this->only($this->fillable),
        'route' => $this->route->update,
      ]);
    }

    public function getDeleteParamsAttribute() {
      return json_encode([
        'fields' => $this->only($this->fillable),
        'route' => $this->route->delete,
      ]);
    }

}
