<?php


namespace Social\Http\Actions;

/**
 * Class WelcomeAction
 * @package Social\Http\Actions
 */
class WelcomeAction
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'message' => 'Social API v1'
        ];
    }
}
