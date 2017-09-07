<?php

namespace Social\Events\Posts;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Social\Contracts\Transformers\PostTransformer;
use Social\Entities\Post;

/**
 * Class PostWasPublishedEvent
 * @package Social\Events\Posts
 */
final class PostWasPublishedEvent implements ShouldBroadcast
{
    /**
     * @var Post
     */
    private $post;

    /**
     * PostWasPublishedEvent constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user.' . $this->post->getUserId());
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'post.created';
    }

    /**
     * @return array
     */
    public function broadcastWith(): array
    {
        /**
         * TODO Refactor using an OOP pattern.
         *      DI via constructor is not possible here. Events should remain pure DTO's.
         *
         *      We cannot pass the transformed Post array directly via the constructor because:
         *          - We need access to the Post entity getters (which might mutate the attribute value).
         *          - Event listeners should use the Post entity and not the transformed Post array.
         *
         *      Passing both the Post entity and transformer Post array via constructor injection feels hackish.
         *
         * @var PostTransformer $postTransformer
         */
        $postTransformer = app()->make(PostTransformer::class);

        return $postTransformer->transform($this->post);
    }
}
