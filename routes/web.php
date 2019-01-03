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

Route::get('/login', 'LoginController@login')->name('login');
Route::post('/authenticate', 'LoginController@authenticate');

Route::get('/register', 'RegisterController@register');
Route::post('/registrate', 'RegisterController@registrate');


Route::get('/', 'PostsController@index')->name('home');
Route::get('/post/{id}', 'PostsController@showPost');
Route::get('/home', 'PostsController@index');

Route::get('/tagposts/{id}', 'PostsController@showPostWidthTags');


Route::middleware('auth')->group(function() {
    Route::get('/logout', 'LoginController@logout');

    Route::get('/addpost', 'PostsController@addPost')->name('addpost');
    Route::get('/editpost/{id}', 'PostsController@editPost');
    Route::post('/storepost', 'PostsController@storePost');
    Route::post('/updatepost', 'PostsController@updatePost');
    Route::get('/deletepost/{id}', 'PostsController@deletePost');

    Route::get('/comments', 'CommentController@index')->name('comments');
    Route::get('/addcomment', 'CommentController@addPost');
    Route::post('/storecomment', 'CommentController@storeComment');

    Route::get('/deletecomment/{id}', 'CommentController@deleteComment');
    Route::get('/editcomment/{id}', 'CommentController@editComment');
    Route::post('/updatecomment', 'CommentController@updateComment');

    Route::get('/tags', 'TagController@index')->name('tags');
    Route::get('/edittag/{id}', 'TagController@editTag');
    Route::post('/storetag', 'TagController@storeTag');
    Route::post('/updatetag', 'TagController@updateTag');
    Route::get('/deletetag/{id}', 'TagController@deleteTag');
});