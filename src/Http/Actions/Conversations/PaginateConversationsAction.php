<?php

namespace Social\Http\Actions\Conversations;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Social\Contracts\ConversationRepository;

/**
 * Class PaginateConversationsAction
 * @package Social\Http\Actions\Conversations
 */
class PaginateConversationsAction
{
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    /**
     * PaginateConversationsAction constructor.
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @param Request $request
     * @return Paginator
     */
    public function __invoke(Request $request): Paginator
    {
        return $this->conversationRepository->paginate($request->user()->getAuthIdentifier());
    }
}