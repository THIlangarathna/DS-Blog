<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// View Blogs
Route::middleware('auth:api')->get('/Blog', 'Api\BlogController@index');

// Store image
Route::middleware('auth:api')->post('/Img', 'Api\BlogController@storeimg');

// Store Blog
Route::middleware('auth:api')->post('/Blog', 'Api\BlogController@store');

// Edit Blog View
Route::middleware('auth:api')->get('/Blog{id}','Api\BlogController@edit');

// Update Blog
Route::middleware('auth:api')->put('/Blog/{id}','Api\BlogController@update');

// Show Blog
Route::middleware('auth:api')->get('/Blogs{id}', 'Api\BlogController@show');

//Delete Blog
Route::middleware('auth:api')->delete('/Blog/{id}', 'Api\BlogController@destroy');

// Store comment
Route::middleware('auth:api')->post('/Comment', 'Api\CommentController@store');

// Update Comment
Route::middleware('auth:api')->put('/Comment/{id}','Api\CommentController@update');

//Delete Comment
Route::middleware('auth:api')->delete('/Comment/{id}', 'Api\CommentController@destroy');

