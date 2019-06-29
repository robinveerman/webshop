<?php

namespace App\Repository;

use App\Entity\Kortingscode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Kortingscode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kortingscode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kortingscode[]    findAll()
 * @method Kortingscode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KortingscodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Kortingscode::class);
    }

    // /**
    //  * @return Kortingscode[] Returns an array of Kortingscode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kortingscode
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
