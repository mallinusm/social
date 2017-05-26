<?php

namespace Social\Http\Actions\Messages;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\{
    Conversation, Message, User
};

/**
 * Class SendMessageAction
 * @package Social\Http\Actions\Messages
 */
class SendMessageAction
{
    use ValidatesRequests;

    /***
     * @param Conversation $conversation
     * @param Request $request
     * @return Message
     */
    public function __invoke(Conversation $conversation, Request $request): Message
    {
        $this->validate($request, array_except(Message::$createRules, ['conversation_id', 'user_id']));

        /**
         * @var User $user
         */
        $user = $request->user();

        return $conversation->messages()->create([
            'content' => $request->input('content'),
            'user_id' => $user->getAuthIdentifier()
        ])->load('user');
    }
}