<?php

namespace Tests\Feature;

/**
 * Class ExampleTest
 * @package Tests\Feature
 */
class ExampleTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testBasicTest(): void
    {
        $this->dontSeeIsAuthenticated('api')
            ->getJson('api/v1')
            ->assertStatus(200)
            ->assertJson(['message' => 'Social API v1']);
    }
}
