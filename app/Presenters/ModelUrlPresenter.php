<?php

namespace App\Presenters;


use App\Model;

class ModelUrlPresenter extends UrlPresenter
{
  public function __construct(Model $model, array $options = [])
  {
    parent::__construct($model, $options);
  }


  public function __get($name)
  {
    if(method_exists($this, $name)) {
      return $this->{$name}();
    }

    return $this->{$name};
  }


  public function index()
  {
    return $this->routeFor('index');
  }

  public function create()
  {
    return $this->routeFor('create');
  }

  public function store()
  {
    return $this->routeFor('store');
  }

  public function show()
  {
    return $this->routeFor('show', [$this->model]);
  }

  public function edit()
  {
    return $this->routeFor('edit', [$this->model]);
  }

  public function update()
  {
    return $this->routeFor('update', [$this->model]);
  }

  public function delete()
  {
    return $this->routeFor('delete', [$this->model]);
  }

}