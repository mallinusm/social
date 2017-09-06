<?php

namespace Tests\Features\Posts;

use Symfony\Component\HttpFoundation\Response;
use Tests\Features\FeatureTestCase;

/**
 * Class PaginateFeedTest
 * @package Tests\Features\Posts
 */
class PaginateFeedTest extends FeatureTestCase
{
    /** @test */
    function paginate_feed_without_json_format()
    {
        $this->assertGuest('api')
            ->get('api/v1/feed')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function paginate_feed_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->getJson('api/v1/feed')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_feed()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $followedUser = $this->createUser();
        $followerUserId = $followedUser->getId();

        $this->createFollower(['author_id' => $userId, 'user_id' => $followerUserId]);

        $post = $this->createPost(['author_id' => $followerUserId, 'user_id' => $followerUserId]);

        $comment = $this->createComment(['user_id' => $followerUserId, 'post_id' => $post->getId()]);

        $upvoteId = $this->getUpvoteId();

        $postReactionable = $this->createReactionable([
            'reactionable_id' => $post->getId(),
            'reactionable_type' => 'posts',
            'reaction_id' => $upvoteId,
            'user_id' => $followerUserId
        ]);

        $commentReactionable = $this->createReactionable([
            'reactionable_id' => $comment->getId(),
            'reactionable_type' => 'comments',
            'reaction_id' => $upvoteId,
            'user_id' => $followerUserId
        ]);

        $userArray = [
            'name' => $followedUser->getName(),
            'username' => $followedUser->getUsername(),
            'avatar' => $followedUser->getAvatar()
        ];

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->getJson('api/v1/feed')
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
                            'user' => $this->userJsonStructure(),
                            'reactionables' => [
                                'upvotes' => [
                                    $this->reactionableJsonStructure()
                                ],
                                'downvotes',
                                'upvote',
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
                        'upvote',
                        'downvote',
                        'has_upvoted',
                        'has_downvoted'
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
                                'upvotes' => [
                                    [
                                        'id' => $commentReactionable->getId(),
                                        'reaction_id' => $commentReactionable->getReactionId(),
                                        'reactionable_id' => $commentReactionable->getReactionableId(),
                                        'reactionable_type' => $commentReactionable->getReactionableType(),
                                        'created_at' => $commentReactionable->getCreatedAt(),
                                        'updated_at' => $commentReactionable->getUpdatedAt(),
                                        'user' => $userArray
                                    ]
                                ],
                                'downvotes' => [],
                                'upvote' => null,
                                'downvote' => null,
                                'has_upvoted' => false,
                                'has_downvoted' => false
                            ]
                        ]
                    ],
                    'reactionables' => [
                        'upvotes' => [
                            [
                                'id' => $postReactionable->getId(),
                                'reaction_id' => $postReactionable->getReactionId(),
                                'reactionable_id' => $postReactionable->getReactionableId(),
                                'reactionable_type' => $postReactionable->getReactionableType(),
                                'created_at' => $postReactionable->getCreatedAt(),
                                'updated_at' => $postReactionable->getUpdatedAt(),
                                'user' => $userArray
                            ]
                        ],
                        'downvotes' => [],
                        'upvote' => null,
                        'downvote' => null,
                        'has_upvoted' => false,
                        'has_downvoted' => false
                    ]
                ]
            ]);
    }
}
