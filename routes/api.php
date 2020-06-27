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


Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('logout', 'Auth\LogoutController@logout');
        Route::put('password', 'Auth\PasswordController@update');
    });

    Route::group(['prefix' => 'security'], function () {
        Route::group(['prefix' => 'role'], function () {
            Route::get('', 'Security\RoleController@get');
        });
    });

    Route::group(['prefix' => 'post'], function () {
        Route::get('', 'PostController@index');
        Route::post('', 'PostController@store');

        Route::group(['prefix' => 'comment'], function () {
            Route::delete('{comment}', 'PostCommentController@destroy');
        });

        Route::group(['prefix' => '{post}'], function () {
            Route::put('', 'PostController@update');
            Route::delete('', 'PostController@destroy');

            Route::post('comment', 'PostCommentController@store');

            Route::post('likes', 'PostLikeController@store');
            Route::delete('likes', 'PostLikeController@destroy');
        });
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('', 'Post\CategoryController@index');
        Route::post('', 'Post\CategoryController@store');

        Route::group(['prefix' => '{category}'], function () {
            Route::put('', 'Post\CategoryController@update');
            Route::delete('', 'Post\CategoryController@destroy');
            Route::patch('', 'Post\CategoryController@restore');
        });
    });


    Route::group(['prefix' => 'user'], function () {
        Route::get('', 'UserController@index');
        Route::put('me', 'UserController@update');
        Route::get('{user}', 'UserController@show');
        Route::put('{user}/roles', 'UserController@setRoles');

        Route::apiResource('comments', '`UserCommentController')->only('index');
        Route::apiResource('posts', 'UserPostController')->only('index');
    });

    Route::group(['prefix' => 'media'], function () {
        Route::post('', 'MediaController@store');
        Route::delete('{media_id}', 'MediaController@destroy');
    });

    Route::group(['prefix' => 'link'], function () {
        Route::post('', 'LinkController@store');

        Route::group(['prefix' => '{link}'], function () {
            Route::get('', 'LinkController@getOne');
            Route::put('', 'LinkController@update');
            Route::delete('', 'LinkController@delete');
            Route::patch('', 'LinkController@restore');
        });
    });

    Route::group(['prefix' => 'event'], function () {
        Route::post('', 'EventController@store');

        Route::group(['prefix' => '{event}'], function () {
            Route::put('', 'EventController@update');
            Route::delete('', 'EventController@destroy');
            Route::patch('', 'EventController@restore');
        });
    });

    Route::group(['prefix' => 'gallery'], function () {
        Route::post('', 'Gallery\GalleryController@store');

        Route::group(['prefix' => '{gallery}'], function () {
            Route::put('', 'Gallery\GalleryController@update');
            Route::delete('', 'Gallery\GalleryController@destroy');
            Route::patch('', 'Gallery\GalleryController@restore');

            Route::post('picture', 'Gallery\GalleryPictureController@store');
        });

        Route::group(['prefix' => 'picture'], function () {
            Route::group(['prefix' => '{gallery_picture}'], function () {
                Route::put('', 'Gallery\GalleryPictureController@update');
                Route::delete('', 'Gallery\GalleryPictureController@destroy');
                Route::patch('', 'Gallery\GalleryPictureController@restore');
            });
        });
    });

    Route::group(['prefix' => 'member'], function () {
        Route::post('', 'Misc\MemberController@store');
        Route::put('{member}', 'Misc\MemberController@update');
        Route::delete('{member}', 'Misc\MemberController@delete');
        Route::patch('{member}', 'Misc\MemberController@restore');
    });

    Route::group(['prefix' => 'argument'], function () {
        Route::post('', 'Misc\ArgumentController@store');
        Route::put('{argument}', 'Misc\ArgumentController@update');
        Route::delete('{argument}', 'Misc\ArgumentController@delete');
        Route::patch('{argument}', 'Misc\ArgumentController@restore');
    });


    Route::put('setting', 'PageSettingsController@update');
});

Route::get('setting', 'PageSettingsController@get');

Route::group(['prefix' => 'auth'], function () {
    Route::get('social', 'Auth\SocialAuthController@getSocial');
    Route::post('register', 'Auth\RegisterController@register');
});

Route::group(['prefix' => 'post'], function () {
    Route::get('{post}', 'PostController@getOne');

    Route::get('comments', 'PostCommentController@index');
    Route::get('', 'PostController@index');
    Route::get('{comment}', 'PostCommentController@show');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('', 'Post\CategoryController@index');

    Route::group(['prefix' => '{category}'], function () {
        Route::get('', 'Post\CategoryController@getOne');
    });
});


Route::group(['prefix' => 'event'], function () {
    Route::get('', 'EventController@get');
    Route::get('{event}', 'EventController@getOne');
});

Route::get('link', 'LinkController@get');

// Media
Route::get('media', 'MediaController@index');

Route::group(['prefix' => 'gallery'], function () {
    Route::get('', 'Gallery\GalleryController@index');
    Route::get('{gallery}', 'Gallery\GalleryController@getOne');
});


Route::group(['prefix' => 'member'], function () {
    Route::get('', 'Misc\MemberController@index');
});


Route::group(['prefix' => 'argument'], function () {
    Route::get('', 'Misc\ArgumentController@index');
});
