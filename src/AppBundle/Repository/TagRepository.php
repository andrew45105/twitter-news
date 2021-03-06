<?php

namespace AppBundle\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Find most popular tags
     *
     * @param int $periodDays
     * @param int $limit
     * @return array
     */
    public function findPopular(int $periodDays, int $limit): array
    {
        $query = $this->getEntityManager()->getConnection()->prepare(
            'SELECT
              tg.title
            FROM tags tg
            LEFT JOIN tweets_tags tt ON tt.tag_id = tg.id
            LEFT JOIN tweets tw ON tw.id = tt.tweet_id
            WHERE tw.published_at > DATE_SUB(CURDATE(), INTERVAL :p DAY)
            GROUP BY title 
            ORDER BY count(tg.id) DESC 
            LIMIT :l'
        );

        $query->bindValue('p', $periodDays, \PDO::PARAM_INT);
        $query->bindValue('l', $limit, \PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }
}
