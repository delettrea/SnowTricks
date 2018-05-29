<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

    public function TricksWithOneIllustration($numberFirst, $numberMax)
    {
        $query = $this->_em->createQuery('SELECT t, i FROM App:Tricks t LEFT JOIN t.illustration i GROUP BY t.id ORDER BY t.id DESC');

        $query->setFirstResult($numberFirst);
        $query->setMaxResults($numberMax);

        $result = $query->getResult();
        return $result;
    }
}
