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
Route::get('/ping', function () {
    return "pong";
});


Route::group([], function() {
    Route::post('login', 'AuthController@login');
    Route::post('signUp', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::middleware('auth:api')->post('uploadImage', 'UploadController@uploadImage');
Route::middleware('auth:api')->apiResource('users', 'users');
Route::middleware('auth:api')->apiResource('categories', 'categories');
Route::apiResource('posts', 'posts');
Route::apiResource('posts.comments', 'comments')->shallow();
Route::apiResource('labels', 'labels');
