<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\Expr\Join;
use Exception;
use Social\Contracts\Repositories\{
    PostRepository,
    ReactionableRepository
};
use Social\Entities\Post;

/**
 * Class DoctrinePostRepository
 * @package Social\Repositories
 */
final class DoctrinePostRepository extends DoctrineRepository implements PostRepository
{
    /**
     * @param int $authorId
     * @param string $content
     * @param int $userId
     * @return Post
     * @throws Exception
     */
    public function publish(int $authorId, string $content, int $userId): Post
    {
        $result = $this->getSqlQueryBuilder()
            ->insert('posts')
            ->values([
                'author_id' => ':authorId',
                'content' => ':content',
                'user_id' => ':userId',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'authorId' => $authorId,
                'content' => $content,
                'userId' => $userId,
                'now' => $now = $this->freshTimestamp()
            ])
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the post.');
        }

        return (new Post)->setId($this->lastInsertedId())
            ->setAuthorId($authorId)
            ->setContent($content)
            ->setUserId($userId)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function unpublish(int $id): bool
    {
        return (bool) $this->getDqlQueryBuilder()
            ->delete(Post::class, 'p')
            ->where($this->getDqlExpression()->eq('p.id', ':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    /**
     * @param array $userIds
     * @return Post[]
     */
    public function paginate(array $userIds): array
    {
        $expression = $this->getDqlExpression();

        return $this->getDqlQueryBuilder()
            ->select(['p', 'a', 'u', 'c', 'cu', 'pr', 'cr', 'pru', 'cru'])
            ->from(Post::class, 'p')
            ->leftJoin('p.author', 'a')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.user', 'u')
            ->leftJoin('c.user', 'cu')
            ->leftJoin('p.reactionables', 'pr', Join::WITH, $expression->eq('pr.reactionableType', ':posts'))
            ->leftJoin('c.reactionables', 'cr', Join::WITH, $expression->eq('cr.reactionableType', ':comments'))
            ->leftJoin('pr.user', 'pru')
            ->leftJoin('cr.user', 'cru')
            ->where($expression->in('p.userId', $userIds))
            ->orderBy($expression->desc('p.createdAt'))
            ->addOrderBy($expression->asc('c.createdAt'))
            ->setParameters([
                'posts' => ReactionableRepository::REACTIONABLE_TYPE_POSTS,
                'comments' => ReactionableRepository::REACTIONABLE_TYPE_COMMENTS
            ])
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $id
     * @return Post
     * @throws EntityNotFoundException
     */
    public function find(int $id): Post
    {
        $repository = $this->getEntityManager()->getRepository(Post::class);

        $post = $repository->find($id);

        if ($post === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier($repository->getClassName(), [$id]);
        }

        /* @var Post $post */
        return $post;
    }
}
