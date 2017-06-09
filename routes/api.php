<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Social\Http\Actions\Comments\LeaveCommentAction;
use Social\Http\Actions\Conversations\{
    PaginateConversationsAction, StartConversationAction
};
use Social\Http\Actions\Followers\{
    FollowUserAction, UnfollowUserAction
};
use Social\Http\Actions\Messages\{
    PaginateMessagesAction, SendMessageAction
};
use Social\Http\Actions\Posts\{
    PublishPostAction, PaginatePostsAction, UnpublishPostAction
};
use Social\Http\Actions\Reactions\{
    UndoUpvotePostAction, UpvotePostAction
};
use Social\Http\Actions\Users\{
    RegisterUserAction, VisitUserAction
};

Route::get('/', function() {
   return ['message' => 'Social API v1'];
});

Route::post('/users', RegisterUserAction::class);

Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/users/{user}', VisitUserAction::class);

    Route::post('/users/{user}/posts', PublishPostAction::class);
    Route::get('/users/{user}/posts', PaginatePostsAction::class);
    Route::delete('/posts/{post}', UnpublishPostAction::class);

    Route::post('/posts/{post}/comments', LeaveCommentAction::class);

    Route::get('/conversations', PaginateConversationsAction::class);
    Route::post('/users/{user}/conversations', StartConversationAction::class);

    Route::post('/conversations/{conversation}/messages', SendMessageAction::class);
    Route::get('/conversations/{conversation}/messages', PaginateMessagesAction::class);

    Route::post('/users/{user}/followers', FollowUserAction::class);
    Route::delete('/followers/{follower}', UnfollowUserAction::class);

    Route::post('/posts/{post}/upvote', UpvotePostAction::class);
    Route::delete('/posts/{post}/upvote', UndoUpvotePostAction::class);
});
