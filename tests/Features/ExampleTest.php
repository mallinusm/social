<?php

namespace Tests\Features;

/**
 * Class ExampleTest
 * @package Tests\Features
 */
class ExampleTest extends FeatureTestCase
{
    /**
     * @return void
     */
    public function testBasicTest(): void
    {
        $this->assertGuest('api')
            ->getJson('api/v1')
            ->assertStatus(200)
            ->assertJson(['message' => 'Social API v1']);
    }
}
