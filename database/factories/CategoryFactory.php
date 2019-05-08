<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {

  return [
    'name' => ($name = title_case($faker->words(2, true))),
    'description' => $faker->sentence,
    'slug' => str_slug($name),
    'user_id' => factory(App\User::class)->create()->id,
  ];

});

$factory->state(App\Category::class, 'deleted_category', function ($faker) {
  return [
    'deleted_at' => Carbon\Carbon::now()->subSecond()
  ];
});
