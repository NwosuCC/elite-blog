<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/home', '/posts');
Route::redirect('/', '/posts');

Auth::routes();


Route::name('category.')->group(function () {

    Route::get('/categories', 'CategoryController@index')->name('index');
//    Route::get('/categories/create', 'CategoryController@create')->name('create');
    Route::post('/categories', 'CategoryController@store')->name('store');
//    Route::get('/categories/{category}/edit', 'CategoryController@edit')->name('edit');
//    Route::get('/categories/{category}', 'CategoryController@show')->name('show')->where('name', '[A-Za-z0-9 ]+');
    Route::put('/categories/{category}', 'CategoryController@update')->name('update');

});


//    Route::get('/category/{category}/posts', 'PostController@index')->name('category');
//    Route::get('/author/{user}/posts', 'PostController@index')->name('author');


Route::name('post.')->group(function () {

    Route::get('/posts', 'PostController@index')->name('index');
    Route::get('/posts/by/{user}/in/{category}', 'PostController@index')->name('author.category');
    Route::get('/posts/by/{user}', 'PostController@index_author')->name('author');
    Route::get('/posts/in/{category}', 'PostController@index_category')->name('category');
    Route::get('/posts/create', 'PostController@create')->name('create');
    Route::post('/posts', 'PostController@store')->name('store');
    Route::get('/posts/{post}/edit', 'PostController@edit')->name('edit');
    Route::get('/posts/{post}', 'PostController@show')->name('show')/*->where('name', '[A-Za-z0-9-]+')*/;
    Route::put('/posts/{post}', 'PostController@update')->name('update');
    Route::delete('/posts/{post}', 'PostController@destroy')->name('delete');

});

