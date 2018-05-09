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

}
