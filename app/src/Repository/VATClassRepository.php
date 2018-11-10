<?php

namespace App\Repository;

use App\Entity\VATClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VATClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method VATClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method VATClass[]    findAll()
 * @method VATClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VATClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VATClass::class);
    }

    // /**
    //  * @return VATClass[] Returns an array of VATClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VATClass
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
