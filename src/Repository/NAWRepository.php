<?php

namespace App\Repository;

use App\Entity\NAW;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NAW|null find($id, $lockMode = null, $lockVersion = null)
 * @method NAW|null findOneBy(array $criteria, array $orderBy = null)
 * @method NAW[]    findAll()
 * @method NAW[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NAWRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NAW::class);
    }

    // /**
    //  * @return NAW[] Returns an array of NAW objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NAW
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
