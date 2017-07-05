<?php

namespace Social\Http\Actions\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Commands\Reactionables\ReactCommand;

/**
 * Class ReactAction
 * @package Social\Http\Actions\Reactionables
 */
final class ReactAction
{
    use ValidatesRequests;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * ReactAction constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): array
    {
        $reactionableType = $request->input('reactionable_type', 'posts');

        $this->validate($request, [
            'reaction_id' => 'required|integer|exists:reactions,id',
            'reactionable_type' => 'required|string|in:posts,comments',
            'reactionable_id' => "required|integer|exists:{$reactionableType},id"
        ]);

        $this->dispatcher->dispatchNow(new ReactCommand(
            $request->user()->getId(),
            $request->input('reaction_id'),
            $request->input('reactionable_id'),
            $reactionableType
        ));

        return ['message' => 'Reacted on the entity.'];
    }
}
