<?php

namespace Tests\Feature\Messages;

use Social\Models\Conversation;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class SendMessageTest
 * @package Tests\Feature\Messages
 */
class SendMessageTest extends FeatureTestCase
{
    /** @test */
    function send_messages_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->post('api/v1/conversations/1/messages')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function send_message_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->postJson('api/v1/conversations/1/messages')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function send_message_for_unknown_conversation()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson('api/v1/conversations/123456789/messages')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Conversation::class));
    }

    /** @test */
    function send_message_without_content()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$this->createConversation()->getId()}/messages")
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content field is required.']]);
    }

    /** @test */
    function send_message_with_too_long_content()
    {
        $conversationId = $this->createConversation()->getId();

        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$conversationId}/messages", ['content' => str_random(256)])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson(['content' => ['The content may not be greater than 255 characters.']]);
    }

    /** @test */
    function send_message_when_user_is_not_in_conversation()
    {
        $user = $this->createUser();

        $conversationId = $this->createConversation()->getId();

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->postJson("api/v1/conversations/{$conversationId}/messages", ['content' => $content = str_random()])
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);

        $this->assertDatabaseMissing('messages', [
            'conversation_id' => $conversationId,
            'content' => $content,
            'user_id' => $user->getAuthIdentifier()
        ]);
    }

    /** @test */
    function send_message()
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
