<?php

namespace Tests\Feature\Reactionables;

use Social\Entities\Reactionable;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\FeatureTestCase;

/**
 * Class UndoReactTest
 * @package Tests\Feature\Reactionables
 */
class UndoReactTest extends FeatureTestCase
{
    /** @test */
    function undo_react_without_json_format()
    {
        $this->dontSeeIsAuthenticated('api')
            ->delete('api/v1/reactionables/123456789')
            ->assertStatus(Response::HTTP_NOT_ACCEPTABLE)
            ->assertExactJson($this->onlyJsonSupported());
    }

    /** @test */
    function undo_react_when_unauthenticated()
    {
        $this->dontSeeIsAuthenticated('api')
            ->deleteJson('api/v1/reactionables/123456789')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertExactJson(['error' => 'Unauthenticated.']);
    }

    /** @test */
    function undo_unknown_react()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson('api/v1/reactionables/123456789')
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson($this->entityNotFound(Reactionable::class));
    }

    /** @test */
    function undo_react_when_not_owner()
    {
        $this->actingAs($this->createUser(), 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/reactionables/{$this->createReactionable()->getId()}")
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertExactJson(['error' => 'This action is unauthorized.']);
    }

    /** @test */
    function undo_react()
    {
        $user = $this->createUser();

        $reactionable = $this->createReactionable([
            'user_id' => $user->getId()
        ]);

        $this->actingAs($user, 'api')
            ->seeIsAuthenticated('api')
            ->deleteJson("api/v1/reactionables/{$reactionable->getId()}")
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['message' => 'Undid react.']);

        $this->assertDatabaseMissing('reactionables', $reactionable->toArray());
    }
}
