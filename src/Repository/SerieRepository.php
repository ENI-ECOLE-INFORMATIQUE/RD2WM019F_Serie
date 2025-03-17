<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }


    public function findByGenresAndPopularity(string $genre)
    {

        //en DQL
//        $dql = "
//                SELECT s FROM App\Entity\Serie AS s
//                WHERE s.genres LIKE :genre
//                ORDER BY s.popularity DESC
//                ";
//        $em = $this->getEntityManager();
//        $query = $em->createQuery($dql);
//
//        $query->setParameter('genre', "%$genre%");
//        $query->setMaxResults(50);


        //en querybuilder
        $qb = $this->createQueryBuilder('s');
        $qb
            ->andWhere("s.genres LIKE :genre")
            ->setParameter('genre', "%$genre%")
            ->addOrderBy('s.popularity', 'DESC');

        $query = $qb->getQuery();
        $query->setMaxResults(50);

        return $query->getResult();
    }


    public function findWithPagination(int $page, int $limit = 50)
    {

        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.seasons', 'seasons');
        $qb->addSelect('seasons');
        $qb->addOrderBy('s.popularity', 'DESC');

        $offset = ($page - 1) * $limit;

        $qb
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }


}
