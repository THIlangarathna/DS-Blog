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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// View Blogs
Route::middleware('auth:api')->get('/Blog', 'API\BlogController@index');

// Store image
Route::middleware('auth:api')->post('/Img', 'API\BlogController@storeimg');

// Store Blog
Route::middleware('auth:api')->post('/Blog', 'API\BlogController@store');

// Edit Blog View
Route::middleware('auth:api')->get('/Blog{id}','API\BlogController@edit');

// Update Blog
Route::middleware('auth:api')->put('/Blog/{id}','API\BlogController@update');

// Show Blog
Route::middleware('auth:api')->get('/Blogs{id}', 'API\BlogController@show');

//Delete Blog
Route::middleware('auth:api')->delete('/Blog/{id}', 'API\BlogController@destroy');

// Store comment
Route::middleware('auth:api')->post('/Comment', 'API\CommentController@store');

// Update Comment
Route::middleware('auth:api')->put('/Comment/{id}','API\CommentController@update');

//Delete Comment
Route::middleware('auth:api')->delete('/Comment/{id}', 'API\CommentController@destroy');
