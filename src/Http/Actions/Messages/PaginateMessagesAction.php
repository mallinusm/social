<?php

namespace Social\Http\Actions\Messages;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Models\{
    Conversation, Message
};

/**
 * Class PaginateMessagesAction
 * @package Social\Http\Actions\Messages
 */
class PaginateMessagesAction
{
    use AuthorizesRequests;

    /**
     * @param Conversation $conversation
     * @param Request $request
     * @return Paginator
     */
    public function __invoke(Conversation $conversation, Request $request): Paginator
    {
        $this->authorizeForUser($request->user(), 'read', [Message::class, $conversation]);

        return $conversation->messages()->with('user')->orderBy('created_at', 'DESC')->simplePaginate();
    }
}