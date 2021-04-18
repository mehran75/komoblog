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
    Route::post('login', 'api\AuthController@login');
    Route::post('signUp', 'api\AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');

        Route::post('uploadImage', 'UploadController@uploadImage');
        Route::apiResource('users', 'api\UserController');
        Route::apiResource('categories', 'api\CategoryController');

    });
});

Route::apiResource('posts', 'api\PostController');
Route::apiResource('posts.comments', 'api\CommentController')->shallow();
Route::apiResource('labels', 'api\LabelController');
