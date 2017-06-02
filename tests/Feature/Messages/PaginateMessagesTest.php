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
    /**
     * @return void
     */
    public function testCannotPaginateMessagesWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/conversations/1/messages')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCannotPaginateMessagesForUnknownConversation(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/conversations/123456789/messages')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson($this->modelNotFoundMessage(Conversation::class));
    }

    /**
     * @return void
     */
    public function testCannotPaginateMessagesWhenUserNotInConversation(): void
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->getJson("api/v1/conversations/{$this->createConversation()->getId()}/messages")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(['error' => 'This action is unauthorized.']);
    }

    /**
     * @return void
     */
    public function testCannotPaginateMessages(): void
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