<?php

namespace App\Repository;

use App\Entity\TricksGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TricksGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method TricksGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method TricksGroups[]    findAll()
 * @method TricksGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksGroupsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TricksGroups::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('g')
            ->where('g.something = :value')->setParameter('value', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
