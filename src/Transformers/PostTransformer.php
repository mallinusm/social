<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Services\TransformerService;
use Social\Contracts\Transformers\{
    CommentTransformer as CommentTransformerContract,
    PostTransformer as PostTransformerContract,
    VoteTransformer as VoteTransformerContract
};
use Social\Entities\Post;
use Social\Transformers\Users\UserTransformer;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer implements PostTransformerContract
{
    /**
     * @var CommentTransformerContract
     */
    private $commentTransformer;

    /**
     * @var VoteTransformerContract
     */
    private $voteTransformer;

    /**
     * @var TransformerService
     */
    private $transformerService;

    /**
     * PostTransformer constructor.
     * @param CommentTransformerContract $commentTransformer
     * @param VoteTransformerContract $voteTransformer
     * @param TransformerService $transformerService
     */
    public function __construct(CommentTransformerContract $commentTransformer,
                                VoteTransformerContract $voteTransformer,
                                TransformerService $transformerService)
    {
        $this->commentTransformer = $commentTransformer;
        $this->voteTransformer = $voteTransformer;
        $this->transformerService = $transformerService;
    }

    /**
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        $this->transformerService->setTransformer(UserTransformer::class);

        $user = $this->transformerService
            ->setData($post->getUser())
            ->toArray();

        $author = $this->transformerService
            ->setData($post->getAuthor())
            ->toArray();

        return [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
            'author' => $author,
            'user' => $user,
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
