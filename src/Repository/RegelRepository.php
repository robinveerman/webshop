<?php

namespace App\Repository;

use App\Entity\Regel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Regel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regel[]    findAll()
 * @method Regel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Regel::class);
    }

    // /**
    //  * @return Regel[] Returns an array of Regel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Regel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
