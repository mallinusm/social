<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\Post;
use Social\Models\User;

/**
 * Class PublishPostAction
 * @package Social\Http\Actions\Posts
 */
class PublishPostAction
{
    use ValidatesRequests;

    /**
     * @param User $user
     * @param Request $request
     * @return Post
     */
    public function __invoke(User $user, Request $request): Post
    {
        $this->validate($request, array_except(Post::$createRules, ['author_id', 'user_id']));

        /**
         * @var User $author
         */
        $author = $request->user();

        return $user->posts()->create([
            'author_id' => $author->getAuthIdentifier(),
            'content' => $request->input('content')
        ]);
    }
}