<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Transformers\{
    CommentTransformer as CommentTransformerContract,
    PostTransformer as PostTransformerContract,
    UserTransformer as UserTransformerContract,
    VoteTransformer as VoteTransformerContract
};
use Social\Entities\Post;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer implements PostTransformerContract
{
    /**
     * @var UserTransformerContract
     */
    private $userTransformer;

    /**
     * @var CommentTransformerContract
     */
    private $commentTransformer;

    /**
     * @var VoteTransformerContract
     */
    private $voteTransformer;

    /**
     * PostTransformer constructor.
     * @param UserTransformerContract $userTransformer
     * @param CommentTransformerContract $commentTransformer
     * @param VoteTransformerContract $voteTransformer
     */
    public function __construct(UserTransformerContract $userTransformer,
                                CommentTransformerContract $commentTransformer,
                                VoteTransformerContract $voteTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->commentTransformer = $commentTransformer;
        $this->voteTransformer = $voteTransformer;
    }

    /**
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
            'author' => $this->userTransformer->transform($post->getAuthor()),
            'user' => $this->userTransformer->transform($post->getUser()),
            'comments' => $this->commentTransformer->transformMany($post->getComments()),
            'reactionables' => $this->voteTransformer->transformMany($post->getReactionables())
        ];
    }

    /**
     * @param Post[] $posts
     * @return array
     */
    public function transformMany(array $posts): array
    {
        return (new Collection($posts))->transform(function(Post $post): array {
            return $this->transform($post);
        })->toArray();
    }
}
