<?php

namespace Tests\Features\Posts;

use Social\Entities\Post;
use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class FetchPostTest
 * @package Tests\Features\Posts
 */
class FetchPostTest extends FeatureTestCase
{
    /** @test */
    function fetch_post_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/posts/1')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function fetch_post_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/posts/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function fetch_unknown_post()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/posts/1')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(Post::class, [1]));
    }

    /** @test */
    function fetch_post()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();
        $username = $user->getUsername();

        $post = $this->createPost(['author_id' => $userId, 'user_id' => $userId]);
        $postId = $post->getId();

        $comment = $this->createComment(['user_id' => $userId, 'post_id' => $postId]);
        $commentId = $comment->getId();

        $reactionId = $this->getUpvoteId();

        $postReactionable = $this->createReactionable([
            'reactionable_id' => $postId,
            'reactionable_type' => 'posts',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $commentReactionable = $this->createReactionable([
            'reactionable_id' => $commentId,
            'reactionable_type' => 'comments',
            'reaction_id' => $reactionId,
            'user_id' => $userId
        ]);

        $userArray = [
            'avatar' => $user->getAvatar(),
            'name' => $user->getName(),
            'username' => $username
        ];

        $postUpvoteArray = [
            'id' => $postReactionable->getId(),
            'reaction_id' => $postReactionable->getReactionId(),
            'reactionable_id' => $postReactionable->getReactionableId(),
            'reactionable_type' => $postReactionable->getReactionableType(),
            'created_at' => $postReactionable->getCreatedAt(),
            'updated_at' => $postReactionable->getUpdatedAt(),
            'user' => $userArray
        ];

        $commentUpvoteArray = [
            'id' => $commentReactionable->getId(),
            'reaction_id' => $commentReactionable->getReactionId(),
            'reactionable_id' => $commentReactionable->getReactionableId(),
            'reactionable_type' => $commentReactionable->getReactionableType(),
            'created_at' => $commentReactionable->getCreatedAt(),
            'updated_at' => $commentReactionable->getUpdatedAt(),
            'user' => $userArray
        ];

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson("api/v1/posts/{$postId}")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
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
                        'user' => $this->userJsonStructure(),
                        'reactionables' => [
                            'upvotes' => [
                                $this->reactionableJsonStructure()
                            ],
                            'downvotes',
                            'upvote' => $this->reactionableJsonStructure(),
                            'downvote',
                            'has_upvoted',
                            'has_downvoted'
                        ]
                    ]
                ],
                'reactionables' => [
                    'upvotes' => [
                        $this->reactionableJsonStructure()
                    ],
                    'downvotes',
                    'upvote' => $this->reactionableJsonStructure(),
                    'downvote',
                    'has_upvoted',
                    'has_downvoted'
                ]
            ])
            ->assertExactJson([
                'id' => $postId,
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt(),
                'updated_at' => $post->getUpdatedAt(),
                'user' => $userArray,
                'author' => $userArray,
                'comments' => [
                    [
                        'id' => $commentId,
                        'content' => $comment->getContent(),
                        'post_id' => $comment->getPostId(),
                        'created_at' => $comment->getCreatedAt(),
                        'updated_at' => $comment->getUpdatedAt(),
                        'user' => $userArray,
                        'reactionables' => [
                            'upvotes' => [
                                $commentUpvoteArray
                            ],
                            'upvote' => $commentUpvoteArray,
                            'downvote' => null,
                            'downvotes' => [],
                            'has_upvoted' => true,
                            'has_downvoted' => false
                        ]
                    ]
                ],
                'reactionables' => [
                    'upvotes' => [
                        $postUpvoteArray
                    ],
                    'upvote' => $postUpvoteArray,
                    'downvote' => null,
                    'downvotes' => [],
                    'has_upvoted' => true,
                    'has_downvoted' => false
                ]
            ]);
    }
}
