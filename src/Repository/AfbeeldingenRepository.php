<?php

namespace App\Repository;

use App\Entity\Afbeeldingen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Afbeeldingen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Afbeeldingen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Afbeeldingen[]    findAll()
 * @method Afbeeldingen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AfbeeldingenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Afbeeldingen::class);
    }

    // /**
    //  * @return Afbeeldingen[] Returns an array of Afbeeldingen objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Afbeeldingen
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
