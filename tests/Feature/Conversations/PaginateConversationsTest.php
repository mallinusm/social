<?php

namespace Tests\Feature\Conversations;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class PaginateConversationsTest
 * @package Tests\Feature\Conversations
 */
class PaginateConversationsTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testCannotPaginateConversationsWhenUnauthenticated(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/conversations')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(['error' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function testCanPaginateConversations(): void
    {
        $conversation = $this->createConversation();

        $user = $this->createUser();
        $userId = $user->getAuthIdentifier();

        $author = $this->createUser();
        $authorId = $author->getAuthIdentifier();

        $conversation->users()->attach([$userId, $authorId]);

        $message = $this->createMessage([
            'conversation_id' => $conversation->getId(),
            'user_id' => $authorId
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/conversations')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($conversation->load('users')->toArray())
            ->assertJsonFragment($message->load('user')->toArray());
    }
}