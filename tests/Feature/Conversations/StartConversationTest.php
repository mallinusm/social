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
    /**
     * @return void
     */
    public function testCannotStartConversationWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/users/1/conversations')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotStartConversationWithUnknownUser(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/users/123456789/conversations')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(User::class));
    }

    /**
     * @return void
     */
    public function testCanStartConversation(): void
    {
        $author = $this->createUser();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $response = $this->actingAs($author, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/users/{$userId}/conversations")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['created_at', 'id', 'updated_at']);

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