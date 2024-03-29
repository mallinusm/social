<?php

use Illuminate\Contracts\Routing\Registrar;
use Social\Http\Actions\Comments\{
    LeaveCommentAction,
    RemoveCommentAction
};
use Social\Http\Actions\Followers\{
    FetchFollowersAction,
    FollowUserAction,
    UnfollowUserAction
};
use Social\Http\Actions\Posts\{
    FetchPostAction,
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
    GeneratePasswordResetTokenAction,
    RegisterUserAction,
    ResetPasswordAction,
    SearchUsersAction,
    UpdatePasswordAction,
    UpdateUserAction,
    UploadAvatarAction,
    VisitUserAction
};
use Social\Http\Actions\WelcomeAction;

/**@var $router Registrar */
$router->get('/', WelcomeAction::class);

$router->post('users', RegisterUserAction::class);

$router->post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

$router->post('password-reset-token', GeneratePasswordResetTokenAction::class);
$router->post('reset-password', ResetPasswordAction::class);

$router->group(['middleware' => 'auth:api'], function(Registrar $router) {
    $router->get('user', FetchCurrentUserAction::class);
    $router->get('users', VisitUserAction::class);
    $router->post('avatar', UploadAvatarAction::class);
    $router->get('users/search', SearchUsersAction::class);
    $router->patch('user', UpdateUserAction::class);
    $router->patch('password', UpdatePasswordAction::class);

    $router->post('reactionables', ReactAction::class);
    $router->delete('reactionables/{reactionableId}', UndoReactAction::class);

    $router->post('posts', PublishPostAction::class);
    $router->get('posts', PaginatePostsAction::class);
    $router->delete('posts/{post}', UnpublishPostAction::class);
    $router->get('feed', PaginateFeedAction::class);
    $router->get('posts/{postId}', FetchPostAction::class);

    $router->post('posts/{post}/comments', LeaveCommentAction::class);
    $router->delete('comments/{comment}', RemoveCommentAction::class);

    $router->post('followers', FollowUserAction::class);
    $router->delete('followers', UnfollowUserAction::class);
    $router->get('followers', FetchFollowersAction::class);
});
