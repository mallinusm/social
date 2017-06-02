<?php

namespace Social\Http\Actions\Conversations;

use Illuminate\Http\Request;
use Social\Contracts\ConversationRepository;
use Social\Models\{
    Conversation, User
};

/**
 * Class StartConversationAction
 * @package Social\Http\Actions\Conversations
 */
class StartConversationAction
{
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    /**
     * StartConversationAction constructor.
     * @param ConversationRepository $conversationRepository
     */
    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Conversation
     */
    public function __invoke(User $user, Request $request): Conversation
    {
        $author = $request->user();

        return $this->conversationRepository->start([
            $user->getAuthIdentifier(),
            $author->getAuthIdentifier()
        ])->setAttribute('users', [$user, $author]);
    }
}