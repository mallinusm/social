<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Social\Models\Post;

/**
 * Class UnpublishPostAction
 * @package Social\Http\Actions\Posts
 */
class UnpublishPostAction
{
    use AuthorizesRequests;

    /**
     * @param Post $post
     * @param Request $request
     * @return array
     */
    public function __invoke(Post $post, Request $request): array
    {
        $this->authorizeForUser($request->user(), 'delete', $post);

        $post->delete();

        return ['message' => 'The post was deleted.'];
    }
}