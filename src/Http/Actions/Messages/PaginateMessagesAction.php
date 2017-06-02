<?php

namespace Social\Http\Actions\Messages;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Contracts\MessageRepository;
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
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * PaginateMessagesAction constructor.
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @param Conversation $conversation
     * @param Request $request
     * @return Paginator
     */
    public function __invoke(Conversation $conversation, Request $request): Paginator
    {
        $this->authorizeForUser($request->user(), 'read', [Message::class, $conversation]);

        return $this->messageRepository->paginate($conversation->getId());
    }
}