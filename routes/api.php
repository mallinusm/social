<?php

use Illuminate\Contracts\Routing\Registrar;
use Social\Http\Actions\Comments\{
    LeaveCommentAction,
    PaginateCommentsAction
};
use Social\Http\Actions\Followers\{
    FollowUserAction,
    UnfollowUserAction
};
use Social\Http\Actions\Posts\{
    PaginateFeedAction,
    PublishPostAction,
    PaginatePostsAction,
    UnpublishPostAction
};
use Social\Http\Actions\Reactionables\{
    ReactAction,
    UndoReactAction
};
use Social\Http\Actions\Users\{
    FetchCurrentUserAction,
    RegisterUserAction,
    SearchUsersAction,
    UploadAvatarAction,
    VisitUserAction
};
use Social\Http\Actions\WelcomeAction;

/**@var $router Registrar */
$router->get('/', WelcomeAction::class);

$router->post('users', RegisterUserAction::class);

$router->post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

$router->group(['middleware' => 'auth:api'], function(Registrar $router) {
    $router->get('user', FetchCurrentUserAction::class);
    $router->get('users', VisitUserAction::class);
    $router->post('avatar', UploadAvatarAction::class);
    $router->get('users/search', SearchUsersAction::class);

    $router->post('reactionables', ReactAction::class);
    $router->delete('reactionables/{reactionableId}', UndoReactAction::class);

    $router->post('posts', PublishPostAction::class);
    $router->get('posts', PaginatePostsAction::class);
    $router->delete('posts/{post}', UnpublishPostAction::class);
    $router->get('feed', PaginateFeedAction::class);

    $router->post('posts/{post}/comments', LeaveCommentAction::class);
    $router->get('posts/{post}/comments', PaginateCommentsAction::class);

    $router->post('users/{user}/follow', FollowUserAction::class);
    $router->delete('users/{user}/unfollow', UnfollowUserAction::class);
});
