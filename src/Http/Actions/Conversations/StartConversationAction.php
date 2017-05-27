<?php

namespace Social\Http\Actions\Conversations;

use Illuminate\Http\Request;
use Social\Models\Conversation;
use Social\Models\User;

/**
 * Class StartConversationAction
 * @package Social\Http\Actions\Conversations
 */
class StartConversationAction
{
    /**
     * @param User $user
     * @param Request $request
     * @return Conversation
     */
    public function __invoke(User $user, Request $request): Conversation
    {
        /**
         * @var Conversation $conversation
         */
        $conversation = Conversation::create();

        $conversation->users()->attach([
            $user->getAuthIdentifier(),
            $request->user()->getAuthIdentifier()
        ]);

        return $conversation;
    }
}