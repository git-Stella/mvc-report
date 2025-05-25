<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    public function findOneByIsbn($isbn): ?Library
    {
        return $this->createQueryBuilder('l')
                ->andWhere('l.isbn = :val')
                ->setParameter('val', $isbn)
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }

    public function findByIsbn($isbn): ?Library
    {
        return $this->createQueryBuilder('l')
                ->andWhere('l.isbn = :val')
                ->setParameter('val', $isbn)
                ->orderBy('l.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()
        ;
    }

    //    /**
    //     * @return Library[] Returns an array of Library objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Library
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
