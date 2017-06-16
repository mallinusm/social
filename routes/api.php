<?php

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
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
    PaginateFeedAction, PublishPostAction, PaginatePostsAction, UnpublishPostAction
};
use Social\Http\Actions\Reactions\{
    DownvoteCommentAction, DownvotePostAction, UndoDownvoteCommentAction, UndoDownvotePostAction,
    UndoUpvoteCommentAction, UndoUpvotePostAction, UpvoteCommentAction, UpvotePostAction
};
use Social\Http\Actions\Users\{
    RegisterUserAction, VisitUserAction
};

/**@var $router Registrar */
$router->get('/', function() {
   return ['message' => 'Social API v1'];
});

$router->post('/users', RegisterUserAction::class);

$router->post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

$router->group(['middleware' => 'auth:api'], function(Registrar $router) {
    /**
     * User
     */
    $router->get('/user', function (Request $request) {
        return $request->user();
    });
    $router->get('/users/{user}', VisitUserAction::class);

    /**
     * Post
     */
    $router->post('/users/{user}/posts', PublishPostAction::class);
    $router->get('/users/{user}/posts', PaginatePostsAction::class);
    $router->delete('/posts/{post}', UnpublishPostAction::class);
    $router->get('/feed', PaginateFeedAction::class);

    /**
     * Comment
     */
    $router->post('/posts/{post}/comments', LeaveCommentAction::class);

    /**
     * Conversation
     */
    $router->get('/conversations', PaginateConversationsAction::class);
    $router->post('/users/{user}/conversations', StartConversationAction::class);

    /**
     * Message routes.
     */
    $router->post('/conversations/{conversation}/messages', SendMessageAction::class);
    $router->get('/conversations/{conversation}/messages', PaginateMessagesAction::class);

    /**
     * Follower
     */
    $router->post('/users/{user}/followers', FollowUserAction::class);
    $router->delete('/followers/{follower}', UnfollowUserAction::class);

    /**
     * Upvote
     */
    $router->post('/posts/{post}/upvote', UpvotePostAction::class);
    $router->delete('/posts/{post}/upvote', UndoUpvotePostAction::class);
    $router->post('/comments/{comment}/upvote', UpvoteCommentAction::class);
    $router->delete('/comments/{comment}/upvote', UndoUpvoteCommentAction::class);

    /**
     * Downvote
     */
    $router->post('/posts/{post}/downvote', DownvotePostAction::class);
    $router->post('/comments/{comment}/downvote', DownvoteCommentAction::class);
    $router->delete('/posts/{post}/downvote', UndoDownvotePostAction::class);
    $router->delete('/comments/{comment}/downvote', UndoDownvoteCommentAction::class);
});
