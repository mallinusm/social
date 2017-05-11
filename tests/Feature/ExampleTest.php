<?php

namespace Tests\Feature;

/**
 * Class ExampleTest
 * @package Tests\Feature
 */
class ExampleTest extends FeatureTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest(): void
    {
        $this->get('api/v1')->assertJson(['message' => 'Social API v1'])->assertStatus(200);
    }
}
