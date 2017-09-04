<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\TestCase;

/**
 * Trait AssertsEvents
 * @package Tests\Feature
 */
trait AssertsEvents
{
    /**
     * @param string $event
     * @param callable $callable
     * @throws Exception
     */
    protected function assertEventWasFired(string $event, callable $callable): void
    {
        $this->assertNotEmpty($this->firedEvents);

        /* @var TestCase $this */
        foreach ($this->firedEvents as $firedEvent) {
            if ($firedEvent instanceof $event) {
                $callable($firedEvent);

                return;
            }
        }

        throw new Exception("Event {$event} was not fired correctly.");
    }
}
