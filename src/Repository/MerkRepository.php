<?php

namespace App\Repository;

use App\Entity\Merk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Merk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merk[]    findAll()
 * @method Merk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Merk::class);
    }

    // /**
    //  * @return Merk[] Returns an array of Merk objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Merk
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
