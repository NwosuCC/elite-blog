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

Auth::routes();

Route::redirect('/', '/posts');


Route::name('post.')->group(function () {

    Route::get('/posts', 'PostController@index')->name('index');
    Route::get('/posts/by/{user}', 'PostController@index_author')->name('author');
    Route::get('/posts/create', 'PostController@create')->name('create');
    Route::post('/posts', 'PostController@store')->name('store');
    Route::get('/posts/{post}/edit', 'PostController@edit')->name('edit');
    Route::get('/posts/{post}', 'PostController@show')->name('show')/*->where('name', '[A-Za-z0-9-]+')*/;
    Route::put('/posts/{post}', 'PostController@update')->name('update');
//    Route::delete('/posts/{post}', 'PostController@destroy')->name('delete');

});

