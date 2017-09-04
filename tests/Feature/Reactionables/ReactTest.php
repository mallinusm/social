<?php

namespace Tests\Feature\Reactionables;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class ReactTest
 * @package Tests\Feature\Reactionables
 */
class ReactTest extends FeatureTestCase
{
    /** @test */
    function react_without_json_format()
    {
        $this->assertGuest('api')
            ->post('api/v1/reactionables')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function react_when_unauthenticated()
    {
        $this->assertGuest('api')
            ->postJson('api/v1/reactionables')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    /** @test */
    function react_without_reaction_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reaction_id' => ['The reaction id field is required.']]);
    }

    /** @test */
    function react_with_invalid_reaction_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', ['reaction_id' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reaction_id' => ['The reaction id must be an integer.']]);
    }

    /** @test */
    function react_with_non_existing_reaction_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', ['reaction_id' => 123456789])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reaction_id' => ['The selected reaction id is invalid.']]);
    }

    /** @test */
    function react_without_reactionable_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_type' => ['The reactionable type field is required.']]);
    }

    /** @test */
    function react_with_invalid_reactionable_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', ['reactionable_type' => 123456789])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_type' => [
                'The selected reactionable type is invalid.',
                'The reactionable type must be a string.'
            ]]);
    }

    /** @test */
    function react_with_non_existing_reactionable_type()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', ['reactionable_type' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_type' => ['The selected reactionable type is invalid.']]);
    }

    /** @test */
    function react_without_reactionable_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_id' => ['The reactionable id field is required.']]);
    }

    /** @test */
    function react_with_invalid_reactionable_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', ['reactionable_id' => str_random()])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_id' => ['The reactionable id must be an integer.']]);
    }

    /** @test */
    function react_with_non_existing_post_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', [
                'reactionable_type' => 'posts',
                'reactionable_id' => 123456789
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_id' => ['The selected reactionable id is invalid.']]);
    }

    /** @test */
    function react_with_non_existing_comment_id()
    {
        $this->actingAs($this->createUser(), 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', [
                'reactionable_type' => 'comments',
                'reactionable_id' => 123456789
            ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonFragment(['reactionable_id' => ['The selected reactionable id is invalid.']]);
    }

    /** @test */
    function react_on_post_when_already_reacted()
    {
        $user = $this->createUser();

        $attributes = [
            'reaction_id' => $this->createReaction()->getId(),
            'reactionable_id' => $this->createPost()->getId(),
            'reactionable_type' => 'posts'
        ];

        $this->createReactionable($attributes + ['user_id' => $user->getId()]);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', $attributes)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment(['error' => 'Cannot react twice.']);
    }

    /** @test */
    function react_on_comment_when_already_reacted()
    {
        $user = $this->createUser();

        $attributes = [
            'reaction_id' => $this->createReaction()->getId(),
            'reactionable_id' => $this->createComment()->getId(),
            'reactionable_type' => 'comments'
        ];

        $this->createReactionable($attributes + ['user_id' => $user->getId()]);

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', $attributes)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJsonFragment(['error' => 'Cannot react twice.']);
    }

    /** @test */
    function react_on_post()
    {
        $user = $this->createUser();

        $attributes = [
            'reaction_id' => $this->createReaction()->getId(),
            'reactionable_id' => $this->createPost()->getId(),
            'reactionable_type' => 'posts'
        ];

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', $attributes)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->reactionableJsonStructure())
            ->assertJsonFragment($attributes)
            ->assertJsonMissing(['user_id'])
            ->assertJsonFragment(['user' => [
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
                'username' => $user->getUsername()
            ]]);

        $this->assertDatabaseHas('reactionables', $attributes + ['user_id' => $user->getId()]);
    }

    /** @test */
    function react_on_comment()
    {
        $user = $this->createUser();

        $attributes = [
            'reaction_id' => $this->createReaction()->getId(),
            'reactionable_id' => $this->createComment()->getId(),
            'reactionable_type' => 'comments'
        ];

        $this->actingAs($user, 'api')
            ->assertAuthenticated('api')
            ->postJson('api/v1/reactionables', $attributes)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->reactionableJsonStructure())
            ->assertJsonFragment($attributes)
            ->assertJsonMissing(['user_id'])
            ->assertJsonFragment(['user' => [
                'name' => $user->getName(),
                'avatar' => $user->getAvatar(),
                'username' => $user->getUsername()
            ]]);

        $this->assertDatabaseHas('reactionables', $attributes + ['user_id' => $user->getId()]);
    }
}
