<?php

namespace Tests\Feature\Messages;

use Social\Events\Messages\MessageWasSentEvent;
use Social\Models\Conversation;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class SendMessageTest
 * @package Tests\Feature\Messages
 */
class SendMessageTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotSendMessageWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/conversations/1/messages')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotSendMessageForUnknownConversation(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/conversations/123456789/messages')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(Conversation::class));
    }

    /**
     * @return void
     */
    public function testCannotSendMessageWithoutContent(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$this->createConversation()->getId()}/messages")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['content' => ['The content field is required.']]);
    }

    /**
     * @return void
     */
    public function testCannotSendMessageWithTooLongContent(): void
    {
        $conversationId = $this->createConversation()->getId();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$conversationId}/messages", ['content' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /**
     * @return void
     */
    public function testCannotSendMessageWhenUserNotInConversation(): void
    {
        $user = $this->createUser();

        $conversationId = $this->createConversation()->getId();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$conversationId}/messages", ['content' => $content = str_random()])
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversationId,
            'content' => $content,
            'user_id' => $user->getAuthIdentifier()
        ]);
    }

    /**
     * @return void
     */
    public function testCanSendMessage(): void
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $conversation = $this->createConversation();
        $conversationId = $conversation->getId();
        $conversation->users()->attach($userId);

        $data = ['conversation_id' => $conversationId, 'content' => $content = str_random(), 'user_id' => $userId];

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$conversationId}/messages", ['content' => $content])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['content', 'conversation_id', 'created_at', 'id', 'updated_at', 'user_id'])
            ->assertJsonFragment($data)
            ->assertJsonFragment(['user' => $user->toArray()]);

        $this->assertDatabaseHas('messages', $data);
    }
}