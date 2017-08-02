<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Entities\Post;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * @var ReactionableTransformer
     */
    private $reactionableTransformer;

    /**
     * @var VoteTransformer
     */
    private $voteTransformer;

    /**
     * PostTransformer constructor.
     * @param UserTransformer $userTransformer
     * @param CommentTransformer $commentTransformer
     * @param ReactionableTransformer $reactionableTransformer
     * @param VoteTransformer $voteTransformer
     */
    public function __construct(UserTransformer $userTransformer,
                                CommentTransformer $commentTransformer,
                                ReactionableTransformer $reactionableTransformer,
                                VoteTransformer $voteTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->commentTransformer = $commentTransformer;
        $this->reactionableTransformer = $reactionableTransformer;
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
