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
     * @var PostTransformer
     */
    private $postTransformer;

    /**
     * PostWasPublishedEvent constructor.
     * @param Post $post
     * @param PostTransformer $postTransformer
     */
    public function __construct(Post $post, PostTransformer $postTransformer)
    {
        $this->post = $post;
        $this->postTransformer = $postTransformer;
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
        return $this->postTransformer->transform($this->post);
    }
}
