<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Posts
 */
Route::resource('posts', 'Post\PostController');
Route::post('uploadimage', 'Post\PostController@uploadimage');
Route::post('deleteimage', 'Post\PostController@deleteimage');

/**
 * Users
 */
Route::resource('users', 'User\UserController');
Route::resource('users.posts', 'User\UserPostController');

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
  Route::post('signin', 'SignInController');
  Route::get('me', 'MeController');
});