<?php

namespace Tests\Feature\Posts;

use Illuminate\Http\Response;
use Social\Entities\User;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginatePostsTest
 * @package Tests\Feature\Posts
 */
class PaginatePostsTest extends FeatureTestCase
{
    /** @test */
    function paginate_posts_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/posts')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function paginate_posts_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/posts')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_posts_for_unknown_user()
    {
        $username = str_random();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts?username={$username}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(User::class));
    }

    /** @test */
    function paginate_posts_without_username()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/posts')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username field is required.']]);
    }

    /** @test */
    function paginate_posts_with_too_long_username()
    {
        $username = str_random(256);

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts?username={$username}")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['username' => ['The username may not be greater than 255 characters.']]);
    }

    /** @test */
    function paginate_posts()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();
        $username = $user->getUsername();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $post->getId()]);

        $reactionId = $this->createReaction()->getId();

        $postReactionable = $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $commentReactionable = $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $userArray = [
            'avatar' => $user->getAvatar(),
            'name' => $user->getName(),
            'username' => $username
        ];

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/posts?username={$username}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                [
                    'id',
                    'content',
                    'created_at',
                    'updated_at',
                    'user',
                    'author',
                    'comments' => [
                        [
                            'id',
                            'content',
                            'post_id',
                            'created_at',
                            'updated_at',
                            'user' => [
                                'name',
                                'avatar',
                                'username'
                            ],
                            'reactionables' => [
                                [
                                    'id',
                                    'reaction_id',
                                    'reactionable_id',
                                    'reactionable_type',
                                    'created_at',
                                    'updated_at',
                                    'user' => [
                                        'name',
                                        'avatar',
                                        'username'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'reactionables' => [
                        [
                            'id',
                            'reaction_id',
                            'reactionable_id',
                            'reactionable_type',
                            'created_at',
                            'updated_at',
                            'user' => [
                                'name',
                                'avatar',
                                'username'
                            ]
                        ]
                    ]
                ]
            ])
            ->assertExactJson([
                [
                    'id' => $post->getId(),
                    'content' => $post->getContent(),
                    'created_at' => $post->getCreatedAt(),
                    'updated_at' => $post->getUpdatedAt(),
                    'user' => $userArray,
                    'author' => $userArray,
                    'comments' => [
                        [
                            'id' => $comment->getId(),
                            'content' => $comment->getContent(),
                            'post_id' => $comment->getPostId(),
                            'created_at' => $comment->getCreatedAt(),
                            'updated_at' => $comment->getUpdatedAt(),
                            'user' => $userArray,
                            'reactionables' => [
                                [
                                    'id' => $commentReactionable->getId(),
                                    'reaction_id' => $commentReactionable->getReactionId(),
                                    'reactionable_id' => $commentReactionable->getReactionableId(),
                                    'reactionable_type' => $commentReactionable->getReactionableType(),
                                    'created_at' => $commentReactionable->getCreatedAt(),
                                    'updated_at' => $commentReactionable->getUpdatedAt(),
                                    'user' => $userArray
                                ]
                            ]
                        ]
                    ],
                    'reactionables' => [
                        [
                            'id' => $postReactionable->getId(),
                            'reaction_id' => $postReactionable->getReactionId(),
                            'reactionable_id' => $postReactionable->getReactionableId(),
                            'reactionable_type' => $postReactionable->getReactionableType(),
                            'created_at' => $postReactionable->getCreatedAt(),
                            'updated_at' => $postReactionable->getUpdatedAt(),
                            'user' => $userArray
                        ]
                    ]
                ]
            ]);
    }
}
