<?php

namespace Social\Http\Actions\Conversations;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

/**
 * Class PaginateConversationsAction
 * @package Social\Http\Actions\Conversations
 */
class PaginateConversationsAction
{
    /**
     * @param Request $request
     * @return Paginator
     */
    public function __invoke(Request $request): Paginator
    {
        return $request->user()->conversations()->with(['messages' => function($query): void {
            $query->orderBy('created_at', 'DESC')->take(1);
        }, 'messages.user', 'users'])->orderBy('created_at', 'DESC')->simplePaginate();
    }
}