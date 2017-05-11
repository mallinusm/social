<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Class ExampleTest
 * @package Tests\Feature
 */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest(): void
    {
        $this->get('api/v1')->assertStatus(200);
    }
}
