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
    /** @test */
    function paginate_conversations_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->get('api/v1/conversations')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function paginate_conversations_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1/conversations')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function paginate_conversations()
    {
        $user = $this->createUser();

        $author = $this->createUser();
        $authorId = $author->getAuthIdentifier();

        $conversation = $this->createConversation();
        $conversation->users()->attach([$user->getAuthIdentifier(), $authorId]);

        $message = $this->createMessage(['conversation_id' => $conversation->getId(), 'user_id' => $authorId]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->getJson('api/v1/conversations')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure($this->simplePaginationStructure())
            ->assertJsonFragment($conversation->load('users')->toArray())
            ->assertJsonFragment($message->setAttribute('user', $author->toArray())->toArray());
    }
}
