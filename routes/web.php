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

Route::get('/', function (){
    return redirect('/posts');
});

Route::get('/posts/create', 'PostController@create')->name('post.create');
Route::get('/posts/by/{user}', 'PostController@index')->name('post.user');
Route::post('/posts', 'PostController@store')->name('post.store');
Route::get('/posts', 'PostController@index')->name('post.index');
Route::get('/posts/{post}/edit', 'PostController@edit')->name('post.edit');
Route::get('/posts/{post}', 'PostController@show')->name('post.show');
Route::put('/posts/{post}', 'PostController@update')->name('post.update');
Route::delete('/posts/{post}', 'PostController@destroy')->name('post.delete');
