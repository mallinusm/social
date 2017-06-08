<?php

namespace Tests\Feature\Messages;

use Social\Models\Conversation;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginateMessagesTest
 * @package Tests\Feature\Messages
 */
class PaginateMessagesTest extends FeatureTestCase
{
    /** @test */
    function paginate_messages_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/conversations/1/messages')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_messages_for_unknown_conversation()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/conversations/123456789/messages')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->modelNotFoundMessage(Conversation::class));
    }

    /** @test */
    function paginate_messages_when_user_is_not_in_conversation()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/conversations/{$this->createConversation()->getId()}/messages")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function paginate_messages()
    {
        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $conversation = $this->createConversation();
        $conversationId = $conversation->getId();
        $conversation->users()->attach($userId);

        $message = $this->createMessage(['conversation_id' => $conversationId, 'user_id' => $userId]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/conversations/{$conversation->getId()}/messages")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($message->setAttribute('user', $user->toArray())->toArray());
    }
}
