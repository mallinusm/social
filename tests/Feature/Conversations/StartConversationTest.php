<?php

namespace Tests\Feature\Conversations;

use Social\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class StartConversationTest
 * @package Tests\Feature\Conversations
 */
class StartConversationTest extends FeatureTestCase
{
    /** @test */
    function start_conversation_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/users/1/conversations')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function start_conversation_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/conversations')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function start_conversation_with_unknown_user()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/conversations')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(User::class));
    }

    /** @test */
    function start_conversation()
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $response = $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/conversations")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['created_at', 'id', 'updated_at'])
            ->assertJsonFragment(['users' => [$user->toArray(), $author->toArray()]]);

        $conversationId = $response->json()['id'];

        $this->assertDatabaseHas('conversation_user', [
            'conversation_id' => $conversationId,
            'user_id' => $userId
        ]);

        $this->assertDatabaseHas('conversation_user', [
            'conversation_id' => $conversationId,
            'user_id' => $author->getAuthIdentifier()
        ]);
    }
}
