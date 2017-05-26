<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Social\Http\Actions\Comments\LeaveCommentAction;
use Social\Http\Actions\Conversations\StartConversationAction;
use Social\Http\Actions\Posts\{
    PublishPostAction,
    PaginatePostsAction,
    UnpublishPostAction
};
use Social\Http\Actions\Users\RegisterUserAction;

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

Route::get('/', function() {
   return ['message' => 'Social API v1'];
});

Route::post('/users', RegisterUserAction::class);

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/users/{user}/posts', PublishPostAction::class);
    Route::get('/users/{user}/posts', PaginatePostsAction::class);

    Route::delete('/posts/{post}', UnpublishPostAction::class);
    Route::post('/posts/{post}/comments', LeaveCommentAction::class);

    Route::post('/users/{user}/conversations', StartConversationAction::class);
});
