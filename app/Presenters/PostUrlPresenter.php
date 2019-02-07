<?php

namespace App\Presenters;

use App\Category;
use App\Model;
use App\User;

class PostUrlPresenter extends ModelUrlPresenter
{
  public function __construct(Model $model, array $options = [])
  {
    parent::__construct($model, $options);
  }


  public function index_filters($user, $category)
  {
    switch (true) {
      case !!($user && $category): {
        return $this->index_author_category($user, $category); break;
      }
      case !!($user) : {
        return $this->index_author($user); break;
      }
      case !!($category) : {
        return $this->index_category($category); break;
      }
      default : {
        return $this->index();
      }
    }
  }

  public function index_author_category(User $user = null, Category $category = null)
  {
    return $this->routeFor('author.category', [$user, $category]);
  }

  public function index_author(User $user)
  {
    return $this->routeFor('author', [$user]);
  }

  public function index_category(Category $category)
  {
    return $this->routeFor('category', [$category]);
  }

}