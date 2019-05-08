<?php

use Faker\Generator as Faker;


$factory->define(App\Post::class, function (Faker $faker) {

  return [
    'title' => ($title = $faker->sentence),
    'body' => $faker->paragraph,
    'slug' => str_slug($title),
    'user_id' => factory(App\User::class)->create()->id,
    'category_id' => factory(App\Category::class)->create()->id,
    'published_at' => now(),
  ];

});

$factory->state(App\Post::class, 'not_yet_published', function ($faker) {
  return [
    'published_at' => Carbon\Carbon::now()->addMonth()
  ];
});

$factory->state(App\Post::class, 'deleted_post', function ($faker) {
  return [
    'deleted_at' => Carbon\Carbon::now()->subSecond()
  ];
});

$factory->state(App\Post::class, 'deleted_category', function ($faker) {
  return [
    'category_id' => factory(App\Category::class)->state('deleted_category')->create()->id
  ];
});
