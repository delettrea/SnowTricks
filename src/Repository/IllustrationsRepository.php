<?php

namespace App\Repository;

use App\Entity\Illustrations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Illustrations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Illustrations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Illustrations[]    findAll()
 * @method Illustrations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IllustrationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Illustrations::class);
    }

//    /**
//     * @return Illustrations[] Returns an array of Illustrations objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Illustrations
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
