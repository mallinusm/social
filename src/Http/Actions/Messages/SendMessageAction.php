<?php

namespace Social\Http\Actions\Messages;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\{
    Conversation, Message
};

/**
 * Class SendMessageAction
 * @package Social\Http\Actions\Messages
 */
class SendMessageAction
{
    use ValidatesRequests, AuthorizesRequests;

    /***
     * @param Conversation $conversation
     * @param Request $request
     * @return Message
     */
    public function __invoke(Conversation $conversation, Request $request): Message
    {
        $this->validate($request, array_except(Message::$createRules, ['conversation_id', 'user_id']));

        $this->authorizeForUser($user = $request->user(), 'create', [Message::class, $conversation]);

        return $conversation->messages()->create([
            'content' => $request->input('content'),
            'user_id' => $user->getAuthIdentifier()
        ])->load('user');
    }
}