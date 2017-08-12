<?php

namespace Social\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\Expr;
use Exception;
use Social\Contracts\PostRepository;
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
            ->where('p.id = ?1')
            ->setParameter(1, $id)
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
            ->leftJoin(
                'p.reactionables', 'pr', Expr\Join::WITH, 'pr.reactionableType = ?1'
            )
            ->leftJoin(
                'c.reactionables', 'cr', Expr\Join::WITH, 'cr.reactionableType = ?2'
            )
            ->leftJoin('pr.user', 'pru')
            ->leftJoin('cr.user', 'cru')
            ->where($expression->in('p.userId', $userIds))
            ->orderBy($expression->desc('p.createdAt'))
            ->addOrderBy($expression->asc('c.createdAt'))
            ->setParameter(1, 'posts')
            ->setParameter(2, 'comments')
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
