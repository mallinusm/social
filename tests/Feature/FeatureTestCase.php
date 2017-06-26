<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * Class FeatureTestCase
 * @package Tests\Feature
 */
abstract class FeatureTestCase extends TestCase
{
    use CreatesModels, DatabaseMigrations, LaravelConcerns;
}
