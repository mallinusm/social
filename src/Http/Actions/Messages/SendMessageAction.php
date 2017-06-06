<?php

namespace Social\Http\Actions\Messages;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\MessageRepository;
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

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * SendMessageAction constructor.
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /***
     * @param Conversation $conversation
     * @param Request $request
     * @return Message
     */
    public function __invoke(Conversation $conversation, Request $request): Message
    {
        $this->validate($request, [
            'content' => 'required|string|max:255'
        ]);

        $this->authorizeForUser($user = $request->user(), 'create', [Message::class, $conversation]);

        return $this->messageRepository->send(
            $request->input('content'), $conversation->getId(), $user->getAuthIdentifier()
        )->setAttribute('user', $user);
    }
}
