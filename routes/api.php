<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user/me', function (Request $request) {
    $user = $request->user()->append('all_permissions')->load('roles');

    return compact('user');
});


Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::group(['prefix' => 'post'], function () {
        Route::get('', 'PostController@index');
        Route::post('', 'PostController@store');

        Route::group(['prefix' => 'comment'], function () {
            Route::delete('{comment}', 'PostCommentController@destroy');
        });

        Route::group(['prefix' => '{post}'], function () {
            Route::get('', 'PostController@getOne');
            Route::put('', 'PostController@update');
            Route::delete('', 'PostController@destroy');

            Route::post('commment', 'PostCommentController@store');

            Route::post('likes', 'PostLikeController@store');
            Route::delete('likes', 'PostLikeController@destroy');
        });
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('', 'Post\CategoryController@index');
        Route::post('', 'Post\CategoryController@store');

        Route::group(['prefix' => '{category}'], function () {
            Route::get('', 'Post\CategoryController@getOne');
            Route::put('', 'Post\CategoryController@update');
            Route::delete('', 'Post\CategoryController@destroy');
            Route::patch('', 'Post\CategoryController@restore');
        });
    });

    Route::put('user/me', 'UserController@update');

    Route::group(['prefix' => 'media'], function () {
        Route::post('', 'MediaController@store');
        Route::delete('{media_id}', 'MediaController@destroy');
    });
});

Route::group(['prefix' => 'post'], function () {
    Route::get('comments', 'PostCommentController@index');
    Route::get('', 'PostController@index');
    Route::get('{comment}', 'PostCommentController@show');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('', 'Post\CategoryController@index');
});
Route::group(['prefix' => 'users'], function () {
    Route::apiResource('comments', 'UserCommentController')->only('index');
    Route::apiResource('posts', 'UserPostController')->only('index');
    Route::apiResource('', 'UserController')->only(['index', 'show']);
});

// Media
Route::get('media', 'MediaController@index');
